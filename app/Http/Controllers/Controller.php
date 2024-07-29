<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

abstract class Controller
{
    protected function success($data = null, $message = 'success', $code = 200): Response
    {
        $result = ['data' => $data, 'message' => $message, 'code' => $code];

        return response($result);
    }

    protected function fail($data = null, $message = 'error', $code = 0): Response
    {
        $result = ['data' => $data, 'message' => $message, 'code' => $code];

        return response($result);
    }
}
