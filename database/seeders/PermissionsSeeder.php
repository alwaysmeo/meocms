<?php

namespace Database\Seeders;

use App\Models\Permissions;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		# 创建系统权限列表
		Permissions::query()->insert([
			['parent_id' => Null, 'code' => 'home', 'name' => '首页', 'description' => '首页', 'icon' => 'TinyIconPublicHome', 'url' => '/home', 'slot' => 0, 'level' => 1, 'show' => 1]
		]);
	}
}
