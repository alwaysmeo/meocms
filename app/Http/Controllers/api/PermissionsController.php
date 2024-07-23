<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Services\Common;
use App\Services\Mapping;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{
	/* 获取权限列表 */
	public function list(): Response
	{
		$common = new Common();
		$list = Permissions::query()->where([
			'show' => 1,
			'deleted_at' => null
		])->get();
		return $this->success($common->buildTree($list->toArray()));
	}

	/* 新增修改权限 */
	public function upsert(Request $request): Response
	{
		$req = $request->only(['id', 'parent_id', 'code', 'name', 'description', 'icon', 'path', 'level']);
		$validator = Validator::make($req, [
			'id' => 'integer',
			'parent_id' => 'integer',
			'code' => 'required|max:100',
			'name' => 'required|max:80',
			'description' => 'max:200',
			'icon' => 'max:100',
			'path' => 'required|max:230',
			'level' => 'required|integer'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$is_exist = Permissions::query()->where('code', $req['code'])->first();
		if ($is_exist) return $this->fail(null, Mapping::$code['5001'], 5001);
		Permissions::query()->updateOrCreate(['id' => $req['id'] ?? null], $req);
		return $this->success();
	}

	/* 删除权限 */
	public function delete(Request $request): Response
	{
		$req = $request->only(['id']);
		$validator = Validator::make($req, ['id' => 'required|integer']);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		Permissions::query()->where('id', $req['id'])->update([
			'deleted_at' => date('Y-m-d H:i:s')
		]);
		return $this->success();
	}

	/* 修改权限启用状态 */
	public function changeShow(Request $request): Response
	{
		$req = $request->only(['id', 'show']);
		$validator = Validator::make($req, ['id' => 'required|integer', 'show' => 'required|in:0,1']);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		Permissions::query()->where('id', $req['id'])->update([
			'show' => $req['show']
		]);
		return $this->success();
	}
}
