<?php

namespace App\Services;

class Common
{
	public function ip($request): string
	{
		return $request->server('REMOTE_ADDR') ?? $request->ip() ?? '0.0.0.0';
	}
}
