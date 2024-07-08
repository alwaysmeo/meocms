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
        /* 素材文件上传记录表 */
        Schema::create('upload_record', function (Blueprint $table) {
            $table->id()->primary()->unique();
            $table->ulid('user_ulid')->comment('上传者用户id');
            $table->string('url', 255)->comment('文件路径');
            $table->tinyInteger('type')->default(0)->comment('文件类型【0:其他, 1:图片, 2:视频, 3:文件, 4:音频】');
            $table->string('origin_name', 200)->comment('源文件名称');
            $table->string('suffix', 20)->comment('文件后缀');
            $table->tinyInteger('status')->default(1)->comment('有效状态【0:无效, 1:有效】');
            $table->dateTime('created_at')->useCurrent()->comment('上传时间');
            $table->dateTime('updated_at')->nullable()->useCurrentOnUpdate()->comment('使用时间');
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
        Schema::dropIfExists('upload_record');
    }
};
