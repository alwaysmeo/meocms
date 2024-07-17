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
            $table->integer('organize_id')->comment('组织ID');
            $table->string('name', 30)->comment('角色名称');
            $table->integer('slot')->comment('角色排序');
            $table->tinyInteger('show')->default(1)->comment('是否启用【0:否, 1:是】');
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
    }
};
