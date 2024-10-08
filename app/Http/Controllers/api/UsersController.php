<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AccountRecord;
use App\Models\Permissions;
use App\Models\RoleOrganize;
use App\Models\RolePermissions;
use App\Models\UserOrganize;
use App\Models\UserRole;
use App\Models\Users;
use App\Services\Common;
use App\Services\Mapping;
use App\Services\Query;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    private Common $common;

    private Query $query;

    private array $code;

    public function __construct()
    {
        $this->common = new Common;
        $this->query = new Query;
        $this->code = Mapping::$code;
    }

    /**
     * 获取用户拥有的权限列表
     *
     * @group 用户 - Users
     */
    public function permissionsList(Request $request): Response
    {
        $user = $request->user();
        $role_permission = RolePermissions::query()->find($user['role_info']['id']);
        $list = Permissions::query()
            ->whereNull('deleted_at')
            ->where('show', 1)
            ->where('type', 1)
            ->whereIn('id', json_decode($role_permission['permission_ids']))
            ->orderByRaw('ISNULL(`ORDER`), `ORDER` ASC')
            ->get();

        return $this->success($this->common->buildTree($list->toArray()));
    }

    /**
     * 获取用户拥有的子权限
     *
     * @group 用户 - Users
     */
    public function permissionsChild(Request $request): Response
    {
        $user = $request->user();
        $req = $request->only(['parent_id', 'parent_code']);
        $validator = Validator::make($req, [
            'parent_id' => 'integer',
            'parent_code' => 'max:100',
        ]);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        $role_permission = RolePermissions::query()->find($user['role_info']['id']);
        $permissions = Permissions::query();
        $permissions->whereNull('deleted_at')
            ->where('show', 1)
            ->orderByRaw('ISNULL(`ORDER`), `ORDER` ASC')
            ->whereIn('id', json_decode($role_permission['permission_ids']));
        if (isset($req['parent_id'])) {
            $permissions->where('parent_id', $req['parent_id']);
        } elseif (isset($req['parent_code'])) {
            $permission = Permissions::query()->where('code', $req['parent_code'])->first();
            $permissions->where('parent_id', $permission['id']);
        }
        $permissions->select('id', 'parent_id', 'code', 'name', 'description', 'icon', 'path', 'level', 'show', 'order', 'type');

        return $this->success($permissions->get());
    }

    /**
     * 获取用户列表
     *
     * @group 用户 - Users
     *
     * @bodyParam organize_id int required Example: 1
     * @bodyParam page int Example: 1
     * @bodyParam limit int Example: 10
     * @bodyParam keyword_type string Enum: ulid, email, nickname, phone No-example
     * @bodyParam keyword string No-example
     * @bodyParam created_at string No-example
     * @bodyParam last_login_at string No-example
     * @bodyParam status string Enum: 0, 1 No-example
     */
    public function list(Request $request): Response
    {
        $req = $request->only(['organize_id', 'page', 'limit', 'keyword_type', 'keyword', 'created_at', 'last_login_at', 'status']);
        $validator = Validator::make($req, [
            'organize_id' => 'required|integer',
            'page' => 'integer',
            'limit' => 'integer',
            'keyword_type' => 'in:ulid,email,nickname,phone',
            'keyword' => 'max:100',
            'created_at' => 'max:21',
            'last_login_at' => 'max:21',
            'status' => 'in:0,1',
        ]);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        $organize_id = intval($req['organize_id']);
        $page = isset($req['page']) ? intval($req['page']) : null;
        $limit = isset($req['limit']) ? intval($req['limit']) : null;
        $keyword_type = $req['keyword_type'] ?? null;
        $keyword = $req['keyword'] ?? null;
        $created_at = $req['created_at'] ?? null;
        $last_login_at = $req['last_login_at'] ?? null;
        $status = $req['status'] ?? null;
        $list = Users::query();
        $list->select('ulid', 'email', 'nickname', 'picture', 'phone', 'status', 'last_login_at', 'created_at');
        /* 只查询当前组织下的用户 */
        $list->whereHas('organize_info', function ($query) use ($organize_id) {
            $query->where('id', $organize_id);
        });
        /* 只查询当前组织下的角色 */
        $list->with(['role_info' => function ($query) use ($organize_id) {
            $query->whereHas('organize_info', function ($query) use ($organize_id) {
                $query->where('organize_id', $organize_id);
            })->select('id', 'name');
        }]);
        $list->whereNull('deleted_at');
        $list->orderBy('created_at', 'desc');
        ($keyword_type && $keyword) && $list->where($keyword_type, 'like', '%'.$keyword.'%');
        $created_at && $this->query->whereBetween($list, 'created_at', $created_at);
        $last_login_at && $this->query->whereBetween($list, 'last_login_at', $last_login_at);
        $status != null && $list->where('status', $status);
        $total = $list->count();
        $list->offset(($page - 1) * $limit)->limit($limit);

        return $this->success([
            'list' => $list->get(),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    /**
     * 新增修改用户
     *
     * @group 用户 - Users
     */
    public function upsert(Request $request): Response
    {
        $req = $request->only(['ulid', 'organize_id', 'nickname', 'role_id', 'email', 'phone', 'password']);
        $validator = Validator::make($req, [
            'organize_id' => 'required|integer',
            'ulid' => 'ulid',
            'role_id' => 'required|integer',
            'nickname' => 'required|between:1,30',
            'email' => 'required|email',
            'phone' => 'digits:11',
            'password' => 'required|regex:/^[a-zA-Z0-9]+$/|between:6,20',
        ]);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        /* ulid为空（新增用户） 并且 email 存在于数据库中则返回用户已存在 */
        if (! isset($req['ulid'])) {
            $is_exist = Users::query()->where('email', $req['email'])->whereNull('deleted_at')->first();
            if ($is_exist) {
                return $this->fail(null, $this->code['3002'], 3002);
            }
        }
        /* 添加、修改用户信息 */
        $user = Users::query()->updateOrCreate(['ulid' => $req['ulid'] ?? null], [
            'nickname' => $req['nickname'],
            'email' => $req['email'],
            'phone' => $req['phone'] ?? null,
            'password' => Hash::make($req['password']),
        ]);
        /* 添加、修改用户角色关联 */
        UserRole::query()->firstOrCreate([
            'user_ulid' => $user['ulid'], 'role_id' => $req['role_id']], [
                'user_ulid' => $user['ulid'], 'role_id' => $req['role_id'],
            ]);
        /* 添加、修改用户组织关联 */
        UserOrganize::query()->firstOrCreate([
            'user_ulid' => $user['ulid'], 'organize_id' => $req['organize_id']], [
                'user_ulid' => $user['ulid'], 'organize_id' => $req['organize_id'],
            ]);
        /* 添加、修改角色组织关联 */
        RoleOrganize::query()->firstOrCreate([
            'role_id' => $req['role_id'], 'organize_id' => $req['organize_id']], [
                'role_id' => $req['role_id'], 'organize_id' => $req['organize_id'],
            ]);
        /* 添加账号操作记录 */
        AccountRecord::query()->create([
            'user_ulid' => $user['ulid'],
            'control_user_ulid' => $request->user()->getAuthIdentifier(),
            'type' => isset($req['ulid']) ? 4 : 1, // 1:新增, 4:修改信息
            'description' => isset($req['ulid']) ? '修改用户信息' : '新增用户',
            'ipv4' => $this->common->ipv4($request),
            'ipv6' => $this->common->ipv6($request),
        ]);

        return $this->success();
    }

    /**
     * 用户详情
     *
     * @group 用户 - Users
     */
    public function detail(Request $request): Response
    {
        $req = $request->only(['ulid']);
        $validator = Validator::make($req, ['ulid' => 'required|ulid']);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        $user = Users::query();
        $user->with(['organize_info' => function ($organize_query) {
            $organize_query->select('id', 'name')->whereNull('deleted_at')
                ->with(['role_info' => function ($role_query) {
                    $role_query->select('id', 'name')->whereNull('deleted_at');
                }]);
        }]);
        $user->where('ulid', $req['ulid']);
        $user->select('ulid', 'email', 'nickname', 'picture', 'phone', 'status', 'last_login_at', 'created_at');

        return $this->success($user->first());
    }

    /**
     * 注销删除用户
     *
     * @group 用户 - Users
     */
    public function delete(Request $request): Response
    {
        $req = $request->only(['ulid']);
        $validator = Validator::make($req, ['ulid' => 'required|ulid']);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        Users::query()->where('ulid', $req['ulid'])->update([
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);
        /* 添加账号删除记录 */
        AccountRecord::query()->create([
            'user_ulid' => $req['ulid'],
            'control_user_ulid' => $request->user()->getAuthIdentifier(),
            'type' => 5,
            'description' => '删除用户',
            'ipv4' => $this->common->ipv4($request),
            'ipv6' => $this->common->ipv6($request),
        ]);

        return $this->success();
    }

    /**
     * 修改用户封禁状态
     *
     * @group 用户 - Users
     */
    public function changeStatus(Request $request): Response
    {
        $req = $request->only(['ulid', 'status']);
        $validator = Validator::make($req, ['ulid' => 'required|ulid', 'status' => 'required|in:0,1']);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        Users::query()->where('ulid', $req['ulid'])->update([
            'status' => $req['status'],
        ]);
        /* 添加账号操作记录 */
        AccountRecord::query()->create([
            'user_ulid' => $req['ulid'],
            'control_user_ulid' => $request->user()->getAuthIdentifier(),
            'type' => $req['status'] === 0 ? 6 : 7, // 6:封禁, 7:解封
            'description' => $req['status'] === 0 ? '账号封禁' : '账号解封',
            'ipv4' => $this->common->ipv4($request),
            'ipv6' => $this->common->ipv6($request),
        ]);

        return $this->success();
    }
}
