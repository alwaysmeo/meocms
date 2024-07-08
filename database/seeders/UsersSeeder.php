<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
			'picture' => 1
		]);
	}
}
