<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('P@55word'),
            'role' => 'admin',
            'status' => 1,
        ]);

        User::create([
            'name' => 'Sopian Ji',
            'email' => 'sopian4ji@gmail.com',
            'password' => Hash::make('P@55word'),
            'role' => 'user',
            'status' => 0,
        ]);
    }
}
