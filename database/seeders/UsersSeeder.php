<?php

namespace Database\Seeders;

use App\Models\Organizes;
use App\Models\UploadRecord;
use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		Users::query()->truncate();
		$organize = Organizes::query()->first();
		# 创建系统超级管理员账号
		$user = Users::query()->create([
			'organize_id' => $organize['id'],
			'email' => env('ADMIN_DEFAULT_EMAIL', 'email@email.com'),
			'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', '123456')),
			'nickname' => '系统管理员',
			'picture_id' => 1
		]);
		# 添加用户头像的上传记录
		UploadRecord::query()->create([
			'user_ulid' => $user['ulid'],
			'url' => 'https://picsum.photos/100/100',
			'file_type' => 1,
			'origin_name' => '头像',
			'suffix' => 'jpeg',
			'type' => 2,
		]);
	}
}
