<?php

namespace Database\Seeders;

use App\Models\Organizes;
use Illuminate\Database\Seeder;

class OrganizesSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		Organizes::query()->truncate();
		# 添加系统默认组织
		Organizes::query()->create([
			'name' => '默认组织',
			'description' => '该组织为系统默认组织，可直接修改编辑使用。',
			'order' => 0
		]);
	}
}
