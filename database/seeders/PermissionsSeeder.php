<?php

namespace Database\Seeders;

use App\Models\Permissions;
use App\Models\PermissionsRole;
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
				'icon' => 'AntHomeOutlined',
				'path' => '/home',
				'level' => 1
			],
			[
				'code' => 'system',
				'name' => '系统管理',
				'description' => '系统管理',
				'icon' => 'AntInboxOutlined',
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
			[
				'code' => 'content',
				'name' => '内容管理',
				'description' => '内容管理',
				'icon' => 'AntContainerOutlined',
				'path' => '/content',
				'level' => 1
			],
			[
				'code' => 'content-article',
				'name' => '内容管理',
				'description' => '内容管理-文章管理',
				'path' => '/content/article',
				'level' => 2
			],
			[
				'code' => 'content-model',
				'name' => '内容模型管理',
				'description' => '内容管理-内容模型管理',
				'path' => '/content/model',
				'level' => 2
			],
			[
				'code' => 'content-timing',
				'name' => '定时任务',
				'description' => '内容管理-定时任务',
				'path' => '/content/timing',
				'level' => 2
			],
			[
				'code' => 'column',
				'name' => '栏目管理',
				'description' => '栏目管理',
				'icon' => 'AntProfileOutlined',
				'path' => '/column',
				'level' => 1
			],
			[
				'code' => 'column-model',
				'name' => '栏目模型管理',
				'description' => '栏目管理-栏目模型管理',
				'path' => '/column/model',
				'level' => 2
			],
			[
				'code' => 'statistics',
				'name' => '数据统计',
				'description' => '数据统计',
				'icon' => 'AntLineChartOutlined',
				'path' => '/statistics',
				'level' => 1
			],
			[
				'code' => 'statistics-general',
				'name' => '网站概况',
				'description' => '数据统计-网站概况',
				'path' => '/statistics/general',
				'level' => 2
			],
			[
				'code' => 'statistics-visitor',
				'name' => '访客分析',
				'description' => '数据统计-访客分析',
				'path' => '/statistics/visitor',
				'level' => 2
			],
			[
				'code' => 'statistics-content',
				'name' => '内容数据统计',
				'description' => '数据统计-内容数据统计',
				'path' => '/statistics/content',
				'level' => 2
			],
		];
		$ids = [];
		for ($i = 0; $i < count($data); $i++) {
			$data[$i]['slot'] = $i;
			$ids[] = $i + 1;
		}
		foreach ($data as $item) {
			if ($item['level'] === 2)
				$item['parent_id'] = Permissions::query()->where('level', 1)->latest('id')->value('id');
			Permissions::query()->insert($item);
		}
		PermissionsRole::query()->create([
			'role_id' => 1,
			'permission_ids' => json_encode($ids)
		]);
	}
}
