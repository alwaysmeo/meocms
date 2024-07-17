<?php

namespace Database\Seeders;

use App\Models\Organizes;
use App\Models\Roles;
use App\Models\UploadRecord;
use App\Models\UserOrganize;
use App\Models\UserRole;
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
		# 创建系统超级管理员账号
		$user = Users::query()->create([
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

		# 添加用户组织角色关联记录
		$organize = Organizes::query()->first();
		UserOrganize::query()->create([
			'user_ulid' => $user['ulid'],
			'organize_id' => $organize['id']
		]);

		# 添加用户组织角色关联记录
		$role = Roles::query()->first();
		UserRole::query()->create([
			'user_ulid' => $user['ulid'],
			'role_id' => $role['id']
		]);
	}
}
