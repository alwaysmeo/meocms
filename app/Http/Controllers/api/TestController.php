<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\RoleUser;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestController extends Controller
{
	public function test(Request $request): Response
	{
		return $this->success();
	}
}
