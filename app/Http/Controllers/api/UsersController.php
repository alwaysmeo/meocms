<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Models\PermissionsRole;
use App\Models\UserOrganize;
use App\Models\UserRole;
use App\Models\Users;
use App\Services\Common;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
	/* 获取用户权限列表 */
	public function permissionsList(Request $request): Response
	{
		$user = $request->user();
		$role = UserOrganize::query()->find($user['ulid']);
		$permission = PermissionsRole::query()->find($role['role_id']);
		$list = Permissions::query()
			->where('show', 1)
			->whereIn('id', json_decode($permission['permission_ids']))
			->orderBy('slot')
			->get();
		$common = new Common();
		return $this->success($common->buildTree($list->toArray()));
	}

	/* 获取用户列表 */
	public function list(Request $request): Response
	{
		$req = $request->only(['organize_id', 'page', 'limit', 'search_type', 'keyword']);
		$validator = Validator::make($req, [
			'organize_id' => 'required|integer',
			'page' => 'required|integer',
			'limit' => 'required|integer',
			'search_type' => 'in:ulid,email,nickname,phone',
			'keyword' => 'max:60',
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$organize_id = intval($req['organize_id']);
		$page = intval($req['page']);
		$limit = intval($req['limit']);
		$search_type = $req['search_type'] ?? null;
		$keyword = $req['keyword'] ?? null;
		$list = Users::query();
		$list->select('ulid', 'email', 'nickname', 'picture_id', 'phone', 'status', 'last_login_at', 'created_at');
		# 只查询当前组织的用户
		$list->whereHas('organize_info', function ($query) use ($organize_id) {
			$query->where('id', $organize_id);
		});
		$list->with(['role_info' => function ($query) use ($organize_id) {
			$query->where('organize_id', $organize_id)->select('id', 'name');
		}]);
		$list->with(['organize_info' => function ($query) use ($organize_id) {
			$query->where('id', $organize_id)->select('id', 'name');
		}]);
		$list->where('deleted_at', NULL);
		$list->orderBy('created_at', 'desc');
		($search_type && $keyword) && $list->where($search_type, 'like', '%' . $keyword . '%');
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
