<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestController extends Controller
{
    /**
     * 测试
     *
     * @unauthenticated
     */
    public function test(Request $request): Response
    {
        return $this->success($request);
    }
}
