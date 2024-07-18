<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Organizes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrganizesController extends Controller
{
	/* 获取组织列表 */
	public function list(Request $request): Response
	{
		$req = $request->only(['page', 'limit']);
		$validator = Validator::make($req, ['page' => 'integer', 'limit' => 'integer']);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		$page = isset($req['page']) ?? intval($req['page']);
		$limit = isset($req['limit']) ?? intval($req['limit']);
		$list = Organizes::query();
		$list->where('deleted_at', null);
		$list->select('id', 'name', 'description', 'show', 'slot');
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

}
