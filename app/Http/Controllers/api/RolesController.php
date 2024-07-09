<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Services\Mapping;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
	/* 获取角色列表 */
	public function list(Request $request): Response
	{
		$req = $request->only(['page', 'limit']);
		$validator = Validator::make($req, [
			'page' => 'integer',
			'limit' => 'integer'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$page = $req['page'] ?? 1;
		$limit = $req['limit'] ?? 10;
		$list = Roles::query();
		$list->select('id', 'name', 'slot', 'show');
		$list->orderBy('slot');
		$total = $list->count();
		$list->offset(($page - 1) * $limit)->limit($limit);
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
		$req = $request->only(['role_id', 'name', 'show']);
		$validator = Validator::make($req, [
			'role_id' => 'integer',
			'name' => 'max:30',
			'show' => 'in:0,1'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$input = [
			'id' => $req['role_id'],
			'name' => $req['name'] ?? null,
			'show' => $req['show']
		];
		Roles::query()->updateOrInsert(['id' => $req['role_id']], $input);
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
}
