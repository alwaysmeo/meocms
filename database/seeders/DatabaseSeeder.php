<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        # 创建系统管理员账号
        User::factory()->create([
            'id' => 1,
            'email' => env('ADMIN_DEFAULT_EMAIL', 'email@email.com'),
            'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', '123456')),
            'nickname' => '系统管理员',
            'headimg' => 1,
            'status' => 1
        ]);
    }
}
