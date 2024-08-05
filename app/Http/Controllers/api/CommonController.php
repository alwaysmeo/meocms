<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mews\Captcha\Facades\Captcha;

class CommonController extends Controller
{
    /**
     * 生成验证码
     *
     * @group 公共 - Common
     */
    public function captcha(Request $request): Response
    {
        $req = $request->only(['type']);
        $validator = Validator::make($req, ['type' => Rule::in(['default', 'math', 'flat', 'mini', 'inverse'])]);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        $data = Captcha::create($req['type'] ?? 'math', true);

        return $this->success($data);
    }
}
