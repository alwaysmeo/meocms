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
		/* 用户表 */
		Schema::create('users', function (Blueprint $table) {
			$table->ulid()->primary()->unique()->comment('ulid');
			$table->string('email', 60)->comment('邮箱账号');
			$table->string('password', 255)->comment('账号密码');
			$table->string('token', 128)->nullable()->comment('登录令牌');
			$table->string('nickname', 30)->comment('账号昵称');
			$table->integer('picture')->nullable()->comment('用户头像（关联上传记录ID）');
			$table->bigInteger('phone')->nullable()->comment('手机号码');
			$table->tinyInteger('status')->default(1)->comment('账号状态【0:封禁, 1:正常】');
			$table->dateTime('last_login_at')->nullable()->comment('最后一次登录时间');
			$table->string('platform', 80)->nullable()->comment('登录平台');
			$table->dateTime('created_at')->useCurrent()->comment('注册时间');
			$table->dateTime('updated_at')->nullable()->useCurrentOnUpdate()->comment('更新时间');
			$table->dateTime('deleted_at')->nullable()->comment('注销时间');
			$table->charset('utf8mb4');
			$table->collation('utf8mb4_unicode_ci');
		});

		/* 访问令牌表 */
		Schema::create('personal_access_tokens', function (Blueprint $table) {
			$table->id();
			$table->ulidMorphs('tokenable');
			$table->string('name');
			$table->string('token', 64)->unique();
			$table->text('abilities')->nullable();
			$table->timestamp('last_used_at')->nullable();
			$table->timestamp('expires_at')->nullable();
			$table->timestamps();
		});

		/* 用户、组织关联表 */
		Schema::create('user_organize', function (Blueprint $table) {
			$table->ulid('user_ulid')->comment('用户ID');
			$table->integer('organize_id')->comment('组织ID');
			$table->charset('utf8mb4');
			$table->collation('utf8mb4_unicode_ci');
		});

		/* 用户、角色关联表 */
		Schema::create('user_role', function (Blueprint $table) {
			$table->ulid('user_ulid')->comment('用户ID');
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
		Schema::dropIfExists('users');
		Schema::dropIfExists('personal_access_tokens');
		Schema::dropIfExists('user_organize');
		Schema::dropIfExists('user_role');
	}
};
