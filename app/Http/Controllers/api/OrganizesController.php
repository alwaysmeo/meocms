<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Organizes;
use App\Models\RoleOrganize;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrganizesController extends Controller
{
    /* 获取组织列表 */
    public function list(Request $request): Response
    {
        $req = $request->only(['page', 'limit', 'keyword_type', 'keyword']);
        $validator = Validator::make($req, [
            'page' => 'integer',
            'limit' => 'integer',
            'keyword_type' => 'in:id,name',
            'keyword' => 'max:100',
        ]);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        $page = isset($req['page']) ? intval($req['page']) : null;
        $limit = isset($req['limit']) ? intval($req['limit']) : null;
        $keyword_type = $req['keyword_type'] ?? null;
        $keyword = $req['keyword'] ?? null;
        $list = Organizes::query();
        $list->whereNull('deleted_at');
        $list->select('id', 'name', 'description', 'show', 'order');
        $list->orderByRaw('ISNULL(`ORDER`), `ORDER` ASC');
        ($keyword_type && $keyword) && $list->where($keyword_type, 'like', '%'.$keyword.'%');
        $total = $list->count();
        ($page && $limit) && $list->offset(($page - 1) * $limit)->limit($limit);

        return $this->success([
            'list' => $list->get(),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    /* 新增修改组织 */
    public function upsert(Request $request): Response
    {
        $req = $request->only(['id', 'name', 'description']);
        $validator = Validator::make($req, [
            'id' => 'integer',
            'name' => 'required|max:30',
            'description' => 'max:200',
        ]);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        Organizes::query()->updateOrCreate(['id' => $req['id'] ?? null], $req);

        return $this->success();
    }

    /* 删除组织 */
    public function delete(Request $request): Response
    {
        $req = $request->only(['id']);
        $validator = Validator::make($req, ['id' => 'required|integer']);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        Organizes::query()->where('id', $req['id'])->update([
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->success();
    }

    /* 获取组织关联的用户 */
    public function users(Request $request): Response
    {
        $req = $request->only(['id', 'page', 'limit']);
        $validator = Validator::make($req, [
            'id' => 'required|integer',
            'page' => 'integer',
            'limit' => 'integer',
        ]);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        $page = isset($req['page']) ? intval($req['page']) : null;
        $limit = isset($req['limit']) ? intval($req['limit']) : null;
        $list = Users::query();
        $list->select('ulid', 'nickname', 'phone', 'email');
        $list->whereNull('deleted_at');
        /* 只查询当前组织下的用户 */
        $organize_id = $req['id'];
        $list->whereHas('organize_info', function ($query) use ($organize_id) {
            $query->where('id', $organize_id);
        });
        $total = $list->count();
        $list->offset(($page - 1) * $limit)->limit($limit);

        return $this->success([
            'list' => $list->get(),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    /* 获取组织关联的角色 */
    public function roles(Request $request): Response
    {
        $req = $request->only(['id', 'page', 'limit']);
        $validator = Validator::make($req, [
            'id' => 'required|integer',
            'page' => 'integer',
            'limit' => 'integer',
        ]);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        $page = isset($req['page']) ? intval($req['page']) : null;
        $limit = isset($req['limit']) ? intval($req['limit']) : null;
        $list = RoleOrganize::query();
        $list->where('organize_id', $req['id']);
        $list->with(['role_info' => function ($query) {
            $query->select('id', 'name');
        }]);
        $list->whereHas('role_info', function ($query) {
            $query->whereNull('deleted_at');
        });
        $total = $list->count();
        $list->offset(($page - 1) * $limit)->limit($limit);

        return $this->success([
            'list' => $list->get(),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    /* 修改组织启用状态 */
    public function changeShow(Request $request): Response
    {
        $req = $request->only(['id', 'show']);
        $validator = Validator::make($req, ['id' => 'required|integer', 'show' => 'required|in:0,1']);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        Organizes::query()->where('id', $req['id'])->update([
            'show' => $req['show'],
        ]);

        return $this->success();
    }
}
