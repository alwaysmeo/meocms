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
		/* 内容模型表 */
		Schema::create('content_model', function (Blueprint $table) {
			$table->id();
			$table->string('code', 255)->comment('唯一标识');
			$table->string('name', 120)->comment('名称');
			$table->json('field')->comment('模型字段');
			$table->integer('organize_id')->nullable()->comment('组织ID 【NULL：全站模型, NOT NULL：站点模型】');
			$table->dateTime('created_at')->useCurrent()->comment('创建时间');
			$table->dateTime('updated_at')->nullable()->useCurrentOnUpdate()->comment('更新时间');
			$table->dateTime('deleted_at')->nullable()->comment('删除时间');
			$table->charset('utf8mb4');
			$table->collation('utf8mb4_unicode_ci');
		});

		/* 内容表 */
		Schema::create('content', function (Blueprint $table) {
			$table->id();
			$table->integer('column_id')->comment('栏目ID');
			$table->integer('model_id')->comment('内容模型ID');
			$table->json('data')->comment('内容');
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
        Schema::dropIfExists('content_model');
        Schema::dropIfExists('content');
    }
};
