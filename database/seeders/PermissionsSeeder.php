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
		Permissions::query()->truncate();
		# 创建系统权限列表
		$data = [
			[
				'code' => 'home',
				'name' => '首页',
				'description' => '首页',
				'icon' => 'TinyIconPublicHome',
				'path' => '/home',
				'level' => 1
			],
			[
				'code' => 'system',
				'name' => '系统管理',
				'description' => '系统管理',
				'icon' => 'TinyIconDataSource',
				'path' => '/system',
				'level' => 1
			],
			[
				'code' => 'system-user',
				'name' => '用户管理',
				'description' => '系统管理-用户管理',
				'path' => '/system/user',
				'level' => 2
			],
			[
				'code' => 'system-role',
				'name' => '角色管理',
				'description' => '系统管理-角色管理',
				'path' => '/system/role',
				'level' => 2
			],
			[
				'code' => 'system-permission',
				'name' => '权限管理',
				'description' => '系统管理-权限管理',
				'path' => '/system/permission',
				'level' => 2
			],
		];
		for ($i = 0; $i < count($data); $i++) $data[$i]['slot'] = $i;
		foreach ($data as $item) {
			if ($item['level'] === 2)
				$item['parent_id'] = Permissions::query()->where('level', 1)->latest('id')->value('id');
			Permissions::query()->insert($item);
		}
	}
}
