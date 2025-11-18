<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'user_code' => '2302132',
            'ic_number' => '2302132',
            'password' => Hash::make('2302132'),
            'role' => 'super_admin',
        ]);
        
    }
}
