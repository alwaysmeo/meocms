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
        /* 管理端_用户表 */
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id()->primary()->unique();
            $table->string('email', 60)->comment('邮箱账号');
            $table->string('password', 255)->comment('账号密码');
            $table->string('token', 128)->nullable()->comment('登录令牌');
            $table->string('nickname', 20)->comment('账号昵称');
            $table->integer('headimg')->nullable()->comment('用户头像');
            $table->bigInteger('phone')->nullable()->comment('手机号码');
            $table->tinyInteger('status')->default(1)->comment('账号状态【0:封禁, 1:正常】');
            $table->timestamp('last_login_at')->nullable()->comment('最后一次登录时间');
            $table->timestamp('created_at')->useCurrent()->comment('注册时间');
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate()->comment('更新时间');
            $table->timestamp('deleted_at')->nullable()->comment('注销时间');
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};
