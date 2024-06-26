<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mews\Captcha\Facades\Captcha;

class CommonController extends Controller
{
	public function captcha(Request $request): Response
	{
		$data = Captcha::create('default', true);
		return $this->success($data);
	}
}
