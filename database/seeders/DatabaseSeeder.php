<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User as Account;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Account::create([
            [
                //admin account
                'name' => 'Super Admin',
                'email' => 'superadmin_aksarayotta@gmail.com',
                'password' => Hash::make('superadmin_aksarayotta!!@#@!'), // password = admin123
                'role' => 'admin',
            ],
            [
                //user account
                'name' => 'Regular User',
                'email' => 'user_aksarayotta@gmail.com',
                'password' => Hash::make('user_aksarayotta!!@#@!'), // password = admin123
                'role' => 'admin',
            ]
        ]);
    }
}
