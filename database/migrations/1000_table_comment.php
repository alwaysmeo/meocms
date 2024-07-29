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
            'organizes' => '组织表',
            'roles' => '角色表',
            'role_organize' => '角色组织关联表',
            'permissions' => '权限菜单表',
            'role_permissions' => '角色权限关联表',
            'users' => '用户表',
            'user_organize' => '用户组织关联表',
            'user_role' => '用户角色关联表',
            'personal_access_tokens' => '访问令牌表',
            'account_record' => '账号操作记录表',
        ];
        /* 生成表注释 */
        foreach ($arr as $k => $v) {
            DB::statement("ALTER TABLE $table_prefix$k COMMENT = '".$v."';");
        }
    }
};
