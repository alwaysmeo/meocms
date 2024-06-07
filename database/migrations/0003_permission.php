<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /* 权限菜单表 */
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable()->comment('父级ID');
            $table->string('code', 50)->comment('唯一标识');
            $table->string('name', 30)->comment('权限名称');
            $table->string('description', 200)->nullable()->comment('权限描述');
            $table->string('icon', 30)->nullable()->comment('权限图标');
            $table->string('path', 255)->comment('页面路径地址');
            $table->integer('slot')->comment('菜单排序');
            $table->tinyInteger('level')->default(1)->comment('菜单层级 【1:一级, 2:二级, 3:三级】');
            $table->tinyInteger('show')->default(1)->comment('是否显示【0:隐藏, 1:显示】');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });

        /* 角色权限关联表 */
        Schema::create('permissions_role', function (Blueprint $table) {
            $table->integer('role_id')->unique()->comment('角色ID');
            $table->json('permission_ids')->comment('权限ID列表');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });

        /* 角色表 */
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->comment('角色名称');
            $table->integer('slot')->comment('角色排序');
            $table->tinyInteger('show')->default(1)->comment('是否启用【0:否, 1:是】');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });

        /* 用户角色关联表 */
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('user_id')->unique()->comment('用户ID');
            $table->integer('role_id')->comment('角色ID');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permissions_role');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }
};
