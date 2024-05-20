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
            'migrations' => '数据库迁移表',
            'upload_record' => '资源上传记录表',
            'users' => '用户表',
            'permissions' => '权限菜单表',
            'permissions_role' => '角色所拥有的权限表',
            'role' => '角色表',
            'role_user' => '用户所属的角色表',
        ];
        /* 生成表注释 */
        foreach ($arr as $k => $v) {
            DB::statement("ALTER TABLE $table_prefix$k COMMENT = '".$v."';");
        }
    }
};
