<?php

namespace Database\Seeders;

use App\Models\Organizes;
use App\Models\RoleOrganize;
use App\Models\Roles;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		Roles::query()->truncate();
		# 添加系统超级管理员角色
		$role = Roles::query()->create([
			'name' => '系统超级管理员',
			'slot' => 0
		]);
		$organize = Organizes::query()->first();
		RoleOrganize::query()->create([
			'role_id' => $role['id'],
			'organize_id' => $organize['id']
		]);
	}
}
