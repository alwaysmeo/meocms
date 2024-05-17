<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $table_prefix = env('DB_PREFIX', '');
        $arr = [
            'users' => '用户表',
            'permissions' => '权限菜单表',
            'permissions_role' => '用户所属角色表',
            'role' => '角色表',
            'role_user' => '用户所属角色表',
        ];
        /* 生成表注释 */
        foreach ($arr as $k => $v) {
            DB::statement("ALTER TABLE $table_prefix$k COMMENT = '".$v."';");
        }
    }
};
