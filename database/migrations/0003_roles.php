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
        /* 角色表 */
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->comment('角色名称');
            $table->string('description', 200)->nullable()->comment('角色描述');
            $table->tinyInteger('show')->default(1)->comment('是否启用【0:关闭, 1:开启】');
            $table->integer('order')->nullable()->comment('角色排序');
            $table->dateTime('created_at')->useCurrent()->comment('创建时间');
            $table->dateTime('updated_at')->nullable()->useCurrentOnUpdate()->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });

        /* 组织、角色关联表 */
        Schema::create('role_organize', function (Blueprint $table) {
            $table->ulid('organize_id')->comment('组织ID');
            $table->integer('role_id')->comment('角色ID');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });

        /* 角色权限关联表 */
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->integer('role_id')->unique()->comment('角色ID');
            $table->json('permission_ids')->comment('权限ID列表');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_organize');
        Schema::dropIfExists('role_permissions');
    }
};
