<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
	/* 获取权限列表 */
	public function list(Request $request): Response
	{
		$req = $request->only(['page', 'limit']);
		$validator = Validator::make($req, [
			'page' => 'integer',
			'limit' => 'integer',
			'search_type' => 'in:ulid,email,nickname,phone',
			'keyword' => 'max:60',
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);

		$page = isset($req['page']) ? intval($req['page']) : null;
		$limit = isset($req['limit']) ? intval($req['limit']) : null;
		$search_type = $req['search_type'] ?? null;
		$keyword = $req['keyword'] ?? null;
		$list = Users::query();
		$list->select('ulid', 'email', 'nickname', 'picture', 'phone', 'status', 'last_login_at', 'created_at');
		$list->where('deleted_at', NULL);
		$list->orderBy('created_at', 'desc');
		$search_type && $keyword && $list->where($search_type, 'like', '%' . $keyword . '%');
		$total = $list->count();
		($page && $limit) && $list->offset(($page - 1) * $limit)->limit($limit);
		return $this->success([
			'list' => $list->get(),
			'total' => $total,
			'page' => $page,
			'limit' => $limit
		]);
	}
}
