<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\RoleUser;
use App\Models\Users;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		Roles::query()->truncate();
		# 添加系统超级管理员角色
		Roles::query()->create([
			'name' => '系统超级管理员',
			'slot' => 0
		]);
		$user = Users::query()->first();
		# 设置系统超级管理员角色权限
		RoleUser::query()->create([
			'user_ulid' => $user->ulid,
			'role_id' => 1
		]);
	}
}
