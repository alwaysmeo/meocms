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
        /* 栏目表 */
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
			$table->integer('parent_id')->nullable()->comment('父级ID');
            $table->string('name', 120)->comment('名称');
            $table->string('description', 200)->nullable()->comment('描述');
            $table->string('path', 255)->nullable()->comment('访问路径');
            $table->integer('cover')->nullable()->comment('封面图 【关联上传记录ID】');
            $table->text('external_link')->nullable()->comment('外部链接');
            $table->tinyInteger('show')->default(1)->comment('是否启用 【0:关闭, 1:开启】');
            $table->integer('order')->nullable()->comment('排序');
            $table->dateTime('created_at')->useCurrent()->comment('创建时间');
            $table->dateTime('updated_at')->nullable()->useCurrentOnUpdate()->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });

        /* 组织、栏目关联表 */
        Schema::create('column_organize', function (Blueprint $table) {
            $table->ulid('organize_id')->comment('组织ID');
            $table->integer('column_id')->comment('栏目ID');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('columns');
        Schema::dropIfExists('column_organize');
    }
};
