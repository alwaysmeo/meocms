<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

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
    }
}
