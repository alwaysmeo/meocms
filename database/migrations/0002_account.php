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
		/* 账号操作记录表 */
		Schema::create('account_record', function (Blueprint $table) {
			$table->id();
			$table->ulid('user_id')->comment('用户id');
			$table->ulid('control_user_id')->comment('操作人用户id');
			$table->tinyInteger('type')->default(0)->comment('操作类型 【0:其他, 1:注册, 2:登录 3:登出 4:注销 5:修改信息 6:封禁, 7:解封, 8:重置密码');
			$table->string('description', 200)->nullable()->comment('描述信息');
			$table->ipAddress('ip')->nullable()->comment('操作者IP地址');
			$table->string('longitude', 30)->nullable()->comment('操作者经度');
			$table->string('altitude', 30)->nullable()->comment('操作者纬度');
			$table->string('nation', 120)->nullable()->comment('国家');
			$table->string('province', 120)->nullable()->comment('省/区');
			$table->string('city', 120)->nullable()->comment('城市');
			$table->string('district', 120)->nullable()->comment('地区');
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
		Schema::dropIfExists('account_record');
	}
};
