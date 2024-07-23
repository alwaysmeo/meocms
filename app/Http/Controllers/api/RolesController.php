<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\RoleOrganize;
use App\Models\RolePermissions;
use App\Models\Roles;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
	/* 获取角色列表 */
	public function list(Request $request): Response
	{
		$req = $request->only(['organize_id', 'page', 'limit']);
		$validator = Validator::make($req, [
			'organize_id' => 'required|integer',
			'page' => 'integer',
			'limit' => 'integer'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$organize_id = intval($req['organize_id']);
		$page = isset($req['page']) ? intval($req['page']) : null;
		$limit = isset($req['limit']) ? intval($req['limit']) : null;
		$list = Roles::query();
		$list->whereNull('deleted_at');
		$list->select('id', 'name', 'description', 'slot', 'show');
		# 只查询当前组织的角色
		$list->whereHas('organize_info', function ($query) use ($organize_id) {
			$query->where('id', $organize_id);
		});
		$list->orderBy('slot');
		$total = $list->count();
		($page && $limit) && $list->offset(($page - 1) * $limit)->limit($limit);
		return $this->success([
			'list' => $list->get(),
			'total' => $total,
			'page' => $page,
			'limit' => $limit
		]);
	}

	/* 新增修改角色 */
	public function upsert(Request $request): Response
	{
		$req = $request->only(['organize_id', 'id', 'name', 'description', 'permission_ids']);
		$validator = Validator::make($req, [
			'organize_id' => 'required|integer',
			'id' => 'integer',
			'name' => 'required|max:30',
			'description' => 'max:200',
			'permission_ids' => 'required|list'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$role = Roles::query()->updateOrCreate(['id' => $req['id'] ?? null], $req);
		RoleOrganize::query()->updateOrCreate(
			['role_id' => $role['id'], 'organize_id' => $req['organize_id']],
			['role_id' => $role['id'], 'organize_id' => $req['organize_id']]
		);
		RolePermissions::query()->updateOrCreate(['role_id' => $role['id']], [
			'role_id' => $role['id'],
			'permission_ids' => json_encode($req['permission_ids'])
		]);
		return $this->success();
	}

	/* 删除角色 */
	public function delete(Request $request): Response
	{
		$req = $request->only(['id']);
		$validator = Validator::make($req, ['id' => 'required|integer']);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		Roles::query()->where('id', $req['id'])->update([
			'deleted_at' => date('Y-m-d H:i:s')
		]);
		return $this->success();
	}

	/* 获取角色关联的用户 */
	public function users(Request $request): Response
	{
		$req = $request->only(['id', 'page', 'limit']);
		$validator = Validator::make($req, [
			'id' => 'required|integer',
			'page' => 'integer',
			'limit' => 'integer'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$page = isset($req['page']) ? intval($req['page']) : null;
		$limit = isset($req['limit']) ? intval($req['limit']) : null;
		$list = UserRole::query();
		$list->where('role_id', $req['id']);
		$list->with(['user_info' => function ($query) {
			$query->select('ulid', 'nickname', 'phone', 'email', 'last_login_at', 'picture');
		}]);
		$total = $list->count();
		$list->offset(($page - 1) * $limit)->limit($limit);
		return $this->success([
			'list' => $list->get(),
			'total' => $total,
			'page' => $page,
			'limit' => $limit
		]);
	}

	/* 修改角色启用状态 */
	public function changeShow(Request $request): Response
	{
		$req = $request->only(['id', 'show']);
		$validator = Validator::make($req, ['id' => 'required|integer', 'show' => 'required|in:0,1']);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		Roles::query()->where('id', $req['id'])->update([
			'show' => $req['show']
		]);
		return $this->success();
	}
}
