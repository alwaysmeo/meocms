<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Services\Common;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionsController extends Controller
{
	/* 获取权限列表 */
	public function list(Request $request): Response
	{
		$common = new Common();
		$list = Permissions::query()->get();
		return $this->success($common->buildTree($list->toArray()));
	}
}
