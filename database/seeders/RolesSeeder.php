<?php

namespace Database\Seeders;

use App\Models\Organizes;
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
		$organize = Organizes::query()->first();
		Roles::query()->create([
			'name' => '系统超级管理员',
			'organize_id' => $organize->id,
			'slot' => 0
		]);
	}
}
