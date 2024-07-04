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
	    /* 用户表 */
	    Schema::create('users', function (Blueprint $table) {
		    $table->ulid()->primary()->unique()->comment('ulid');
		    $table->string('email', 60)->comment('邮箱账号');
		    $table->string('password', 255)->comment('账号密码');
		    $table->string('token', 128)->nullable()->comment('登录令牌');
		    $table->string('nickname', 30)->comment('账号昵称');
		    $table->integer('picture')->nullable()->comment('用户头像');
		    $table->bigInteger('phone')->nullable()->comment('手机号码');
		    $table->tinyInteger('status')->default(1)->comment('账号状态【0:封禁, 1:正常】');
		    $table->dateTime('last_login_at')->nullable()->comment('最后一次登录时间');
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
	    Schema::dropIfExists('personal_access_tokens');
    }
};
