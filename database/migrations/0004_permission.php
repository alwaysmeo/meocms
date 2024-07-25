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
            $table->string('code', 100)->comment('唯一标识');
            $table->string('name', 80)->comment('权限名称');
            $table->string('description', 200)->nullable()->comment('权限描述');
            $table->string('icon', 100)->nullable()->comment('权限图标');
            $table->string('path', 255)->comment('页面路径地址');
            $table->tinyInteger('level')->default(1)->comment('菜单层级 【1:一级, 2:二级, 3:三级】');
            $table->tinyInteger('show')->default(1)->comment('是否显示【0:隐藏, 1:显示】');
            $table->integer('slot')->nullable()->comment('菜单排序');
	        $table->dateTime('created_at')->useCurrent()->comment('创建时间');
	        $table->dateTime('updated_at')->nullable()->useCurrentOnUpdate()->comment('更新时间');
	        $table->dateTime('deleted_at')->nullable()->comment('删除时间');
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
    }
};
