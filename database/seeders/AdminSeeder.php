<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Spatie Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);

        // 2. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@whimarket.com'],
            [
                'name' => 'Admin WhiMarket',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'phone' => '081234567890',
                'avatar' => '/assets/logo-whimarket.png',
            ]
        );

        $admin->syncRoles([$adminRole]);
    }
}
