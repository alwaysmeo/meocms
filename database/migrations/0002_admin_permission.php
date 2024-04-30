<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /* 管理端_权限菜单表 */
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->comment('父级ID');
            $table->string('code', 30)->comment('唯一标识');
            $table->string('name', 30)->comment('权限名称');
            $table->string('description', 120)->nullable()->comment('权限描述');
            $table->string('icon', 20)->nullable()->comment('权限图标');
            $table->string('url', 255)->comment('URL地址');
            $table->integer('slot')->comment('菜单排序');
            $table->tinyInteger('level')->default(1)->comment('菜单层级 【1:一级, 2:二级, 3:三级】');
            $table->tinyInteger('show')->default(1)->comment('是否显示【0:隐藏, 1:显示】');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });

        /* 管理端_用户所属角色表 */
        Schema::create('admin_permissions_role', function (Blueprint $table) {
            $table->integer('permissions_ids')->comment('权限ID列表');
            $table->integer('role_id')->comment('角色ID');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });

        /* 管理端_角色表 */
        Schema::create('admin_role', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->comment('角色名称');
            $table->integer('slot')->comment('角色排序');
            $table->tinyInteger('show')->default(1)->comment('是否启用【0:否, 1:是】');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });

        /* 管理端_用户所属角色表 */
        Schema::create('admin_role_user', function (Blueprint $table) {
            $table->integer('user_id')->comment('用户ID');
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
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_role');
        Schema::dropIfExists('admin_user_role');
    }
};
