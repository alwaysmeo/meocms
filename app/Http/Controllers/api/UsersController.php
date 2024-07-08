<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Models\PermissionsRole;
use App\Models\RoleUser;
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
		$role = RoleUser::query()->find($user['ulid']);
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
		$req = $request->only(['page', 'limit', 'search_type', 'keyword']);
		$validator = Validator::make($req, [
			'page' => 'integer',
			'limit' => 'integer',
			'search_type' => 'in:ulid,email,nickname,phone',
			'keyword' => 'max:60',
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$page = $req['page'] ?? 1;
		$limit = $req['limit'] ?? 10;
		$search_type = $req['search_type'] ?? null;
		$keyword = $req['keyword'] ?? null;
		$list = Users::query();
		$list->select('ulid', 'email', 'nickname', 'picture', 'phone', 'status', 'last_login_at', 'created_at');
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
