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
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    private Common $common;

    private array $code;

    public function __construct()
    {
        $this->common = new Common;
        $this->code = Mapping::$code;
    }

    /* 获取用户权限列表 */
    public function permissionsList(Request $request): Response
    {
        $user = $request->user();
        $role_permission = RolePermissions::query()->find($user['role_info']['id']);
        $list = Permissions::query()
            ->whereNull('deleted_at')
            ->where('show', 1)
            ->whereIn('id', json_decode($role_permission['permission_ids']))
            ->orderByRaw('ISNULL(`ORDER`), `ORDER` ASC')
            ->get();

        return $this->success($this->common->buildTree($list->toArray()));
    }

    /* 获取用户列表 */
    public function list(Request $request): Response
    {
        $req = $request->only(['organize_id', 'page', 'limit', 'keyword_type', 'keyword']);
        $validator = Validator::make($req, [
            'organize_id' => 'required|integer',
            'page' => 'integer',
            'limit' => 'integer',
            'keyword_type' => 'in:ulid,email,nickname,phone',
            'keyword' => 'max:100',
        ]);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        $organize_id = intval($req['organize_id']);
        $page = isset($req['page']) ? intval($req['page']) : null;
        $limit = isset($req['limit']) ? intval($req['limit']) : null;
        $keyword_type = $req['keyword_type'] ?? null;
        $keyword = $req['keyword'] ?? null;
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
        $total = $list->count();
        $list->offset(($page - 1) * $limit)->limit($limit);

        return $this->success([
            'list' => $list->get(),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    /* 新增修改用户 */
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
            'phone' => $req['phone'],
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

    /* 用户详情 */
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

    /* 注销删除用户 */
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

    /* 修改用户封禁状态 */
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
