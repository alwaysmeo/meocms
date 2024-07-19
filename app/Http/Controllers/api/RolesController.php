<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\RoleOrganize;
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
		$list->where('deleted_at', null);
		$list->select('id', 'name', 'slot', 'show');
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
		$req = $request->only(['organize_id', 'role_id', 'name', 'show']);
		$validator = Validator::make($req, [
			'organize_id' => 'required|integer',
			'role_id' => 'integer',
			'name' => 'max:30',
			'show' => 'in:0,1'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$input = $req;
		unset($input['organize_id']);
		if (isset($req['role_id'])) {
			unset($input['role_id']);
			$input['id'] = $req['role_id'];
		}

		$role = Roles::query()->updateOrCreate(['id' => $input['id'] ?? null], $input);
		RoleOrganize::query()->updateOrCreate(
			['role_id' => $role['id'], 'organize_id' => $req['organize_id']],
			['role_id' => $role['id'], 'organize_id' => $req['organize_id']]
		);
		return $this->success();
	}

	/* 删除角色 */
	public function delete(Request $request): Response
	{
		$req = $request->only(['role_id']);
		$validator = Validator::make($req, ['role_id' => 'required|integer']);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$res = Roles::query()->where('id', $req['role_id'])->update([
			'deleted_at' => date('Y-m-d H:i:s')
		]);
		if ($res) return $this->success();
		else return $this->fail();
	}

	/* 获取角色关联的用户 */
	public function users(Request $request): Response
	{
		$req = $request->only(['role_id', 'page', 'limit']);
		$validator = Validator::make($req, [
			'role_id' => 'required|integer',
			'page' => 'integer',
			'limit' => 'integer'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$page = isset($req['page']) ? intval($req['page']) : null;
		$limit = isset($req['limit']) ? intval($req['limit']) : null;
		$list = UserRole::query();
		$list->where('role_id', $req['role_id']);
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
}
