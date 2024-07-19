<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Models\PermissionsRole;
use App\Models\RoleOrganize;
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
	/* 获取用户权限列表 */
	public function permissionsList(Request $request): Response
	{
		$user = $request->user();
		$permission = PermissionsRole::query()->find($user['role_info']['id']);
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
			'page' => 'integer',
			'limit' => 'integer',
			'search_type' => 'in:ulid,email,nickname,phone',
			'keyword' => 'max:60',
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$organize_id = intval($req['organize_id']);
		$page = isset($req['page']) ? intval($req['page']) : null;
		$limit = isset($req['limit']) ? intval($req['limit']) : null;
		$search_type = $req['search_type'] ?? null;
		$keyword = $req['keyword'] ?? null;
		$list = Users::query();
		$list->select('ulid', 'email', 'nickname', 'picture', 'phone', 'status', 'last_login_at', 'created_at');
		# 只查询当前组织的用户
		$list->whereHas('organize_info', function ($query) use ($organize_id) {
			$query->where('id', $organize_id);
		});
		$list->with(['role_info' => function ($query) use ($organize_id) {
			$query->whereHas('organize_info', function ($query) use ($organize_id) {
				$query->where('organize_id', $organize_id);
			})->select('id', 'name');
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
			'password' => 'required|regex:/^[a-zA-Z0-9]+$/|between:6,20'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		if (!isset($req['ulid'])) {
			$is_exist = Users::query()->where('email', $req['email'])->whereNull('deleted_at')->first();
			if ($is_exist) return $this->fail(null, Mapping::$code['3002'], 3002);
		}
		$user = Users::query()->updateOrCreate(['ulid' => $req['ulid'] ?? null], [
			'nickname' => $req['nickname'],
			'email' => $req['email'],
			'phone' => $req['phone'],
			'password' => Hash::make($req['password'])
		]);
		UserRole::query()->firstOrCreate([
			'user_ulid' => $user['ulid'], 'role_id' => $req['role_id']], [
			'user_ulid' => $user['ulid'], 'role_id' => $req['role_id']
		]);
		UserOrganize::query()->firstOrCreate([
			'user_ulid' => $user['ulid'], 'organize_id' => $req['organize_id']], [
			'user_ulid' => $user['ulid'], 'organize_id' => $req['organize_id']
		]);
		RoleOrganize::query()->firstOrCreate([
			'role_id' => $req['role_id'], 'organize_id' => $req['organize_id']], [
			'role_id' => $req['role_id'], 'organize_id' => $req['organize_id']
		]);
		return $this->success();
	}
}
