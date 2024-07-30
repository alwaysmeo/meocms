<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Models\RolePermissions;
use App\Services\Common;
use App\Services\Mapping;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{
	private Common $common;

	private array $code;

	public function __construct()
	{
		$this->common = new Common;
		$this->code = Mapping::$code;
	}

	/* 获取权限列表 */
	public function list(): Response
	{
		$list = Permissions::query()
			->select('id', 'parent_id', 'code', 'name', 'description', 'icon', 'path', 'level', 'show', 'order', 'type')
			->whereNull('deleted_at')
			->orderByRaw('ISNULL(`ORDER`), `ORDER` ASC')
			->get();

		return $this->success($this->common->buildTree($list->toArray()));
	}

	/* 新增修改权限 */
	public function upsert(Request $request): Response
	{
		$req = $request->only(['id', 'parent_id', 'code', 'name', 'description', 'icon', 'path', 'level', 'type']);
		$validator = Validator::make($req, [
			'id' => 'integer',
			'parent_id' => 'integer',
			'code' => 'required|max:255',
			'name' => 'required|max:80',
			'description' => 'max:200',
			'icon' => 'max:100',
			'path' => 'max:255',
			'level' => 'required|integer',
			'type' => 'required|in:1,2',
		]);
		if (!$validator->passes()) {
			return $this->fail(null, $validator->errors()->first(), 5000);
		}
		if (!isset($req['id'])) {
			$is_exist = Permissions::query()->where('code', $req['code'])->first();
			if ($is_exist) {
				return $this->fail(null, $this->code['5001'], 5001);
			}
		}
		Permissions::query()->updateOrCreate(['id' => $req['id'] ?? null], $req);

		return $this->success();
	}

	/* 删除权限 */
	public function delete(Request $request): Response
	{
		$req = $request->only(['id']);
		$validator = Validator::make($req, ['id' => 'required|integer']);
		if (!$validator->passes()) {
			return $this->fail(null, $validator->errors()->first(), 5000);
		}
		/* 禁止删除【首页】权限 */
		if ($req['id'] === 1) {
			return $this->fail(null, $this->code['5002'], 5002);
		}
		Permissions::query()->where('id', $req['id'])->update([
			'deleted_at' => date('Y-m-d H:i:s'),
		]);

		return $this->success();
	}

	/* 获取子权限 */
	public function children(Request $request): Response
	{
		$user = $request->user();
		$req = $request->only(['parent_id', 'parent_code']);
		$validator = Validator::make($req, [
			'parent_id' => 'integer',
			'parent_code' => 'max:100',
		]);
		if (!$validator->passes()) {
			return $this->fail(null, $validator->errors()->first(), 5000);
		}
		$role_permission = RolePermissions::query()->find($user['role_info']['id']);
		$permissions = Permissions::query();
		$permissions->whereNull('deleted_at');
		$permissions->whereIn('id', json_decode($role_permission['permission_ids']));
        if (isset($req['parent_id'])) {
			$permissions->where('parent_id', $req['parent_id']);
		} elseif (isset($req['parent_code'])) {
			$permission = Permissions::query()->where('code', $req['parent_code'])->first();
			$permissions->where('parent_id', $permission['id']);
		}
        $permissions->select('id', 'parent_id', 'code', 'name', 'description', 'icon', 'path', 'level', 'show', 'order', 'type');

        return $this->success($permissions->get());
    }

	/* 修改权限启用状态 */
	public function changeShow(Request $request): Response
	{
		$req = $request->only(['id', 'show']);
		$validator = Validator::make($req, ['id' => 'required|integer', 'show' => 'required|in:0,1']);
		if (!$validator->passes()) {
			return $this->fail(null, $validator->errors()->first(), 5000);
		}
		Permissions::query()->where('id', $req['id'])->update([
			'show' => $req['show'],
		]);

		return $this->success();
	}
}
