<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $table_prefix = env('DB_PREFIX', '');
        $arr = array(
            'admin_users' => '管理端_用户表',
            'admin_permissions' => '管理端_权限菜单表',
            'admin_permissions_role' => '管理端_用户所属角色表',
            'admin_role' => '管理端_角色表',
            'admin_role_user' => '管理端_用户所属角色表',
        );
        /* 生成表注释 */
        foreach ($arr as $k => $v) {
            DB::statement("ALTER TABLE $table_prefix$k COMMENT = '" . $v . "';");
        }
    }
};
