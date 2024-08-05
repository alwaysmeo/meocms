<?php

namespace App\Providers;

use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Knuckles\Camel\Extraction\ExtractedEndpointData;
use Knuckles\Scribe\Scribe;
use Symfony\Component\HttpFoundation\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 注册SQL执行监听器
        if (env('LOG_LEVEL') === 'debug') {
            DB::listen(function ($query) {
                Log::DEBUG('', ['time' => "{$query->time}ms", 'sql' => $query->sql, 'bindings' => $query->bindings]);
            });
        }
        if (class_exists(\Knuckles\Scribe\Scribe::class)) {
            Scribe::beforeResponseCall(function (Request $request, ExtractedEndpointData $endpointData) {
                $user = Users::query()->orderBy('last_login_at', 'desc')->first();
                $request->headers->add(['Authorization' => 'Bearer '.$user['token']]);
            });
        }
    }
}
