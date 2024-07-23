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
		/* 组织表 */
		Schema::create('organizes', function (Blueprint $table) {
			$table->id();
			$table->string('name', 80)->comment('组织名称');
			$table->string('description', 200)->nullable()->comment('组织描述');
			$table->tinyInteger('show')->default(1)->comment('是否启用【0:关闭, 1:开启】');
			$table->integer('slot')->default(0)->comment('组织排序');
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
		Schema::dropIfExists('organizes');
	}
};
