<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@warkop.com',
            'password' => Hash::make('password'),
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@warkop.com');
        $this->command->info('Password: password');
    }
}
