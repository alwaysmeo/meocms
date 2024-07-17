<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\UserOrganize;
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
		$page = isset($req['page']) ?? intval($req['page']);
		$limit = isset($req['limit']) ?? intval($req['limit']);
		$list = Roles::query();
		$list->where('organize_id', $req['organize_id']);
		$list->select('id', 'name', 'organize_id', 'slot', 'show');
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
		if (isset($req['role_id'])) {
			unset($input['role_id']);
			$input['id'] = $req['role_id'];
		}
		Roles::query()->updateOrInsert(['id' => isset($input['id']) ?? $input['id']], $input);
		return $this->success();
	}

	/* 删除角色 */
	public function delete(Request $request): Response
	{
		$req = $request->only(['role_id']);
		$validator = Validator::make($req, ['role_id' => 'required|integer']);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$res = Roles::query()->where('id', $req['role_id'])->delete();
		if ($res) return $this->success();
		else return $this->fail();
	}

	/* 获取角色关联的用户 */
	public function users(Request $request): Response
	{
		$req = $request->only(['role_id', 'page', 'limit']);
		$validator = Validator::make($req, [
			'role_id' => 'required|integer',
			'page' => 'required|integer',
			'limit' => 'required|integer'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$page = intval($req['page']);
		$limit = intval($req['limit']);
		$list = UserOrganize::query();
		$list->where('role_id', $req['role_id']);
		$list->with('user_info');
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
