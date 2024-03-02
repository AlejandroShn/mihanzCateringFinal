<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => 'Admin',
                'last_name' => 'admin',
                'address' => 'Manila',
                'contact_number' => '1234567890',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
                'role_id' => 1,
            ],
            [
                'first_name' => 'Cedric',
                'last_name' => 'Iglesias',
                'address' => 'Bulacan',
                'contact_number' => '9876543210',
                'email' => 'cedric@gmail.com',
                'password' => Hash::make('user'),
                'role_id' => 2, 
            ],
            [
                'first_name' => 'Shean',
                'last_name' => 'Alejandro',
                'address' => 'Bulacan',
                'contact_number' => '5555555555',
                'email' => 'shean@gmail.com',
                'password' => Hash::make('user'),
                'role_id' => 2, 
            ],
            [
                'first_name' => 'Kim',
                'last_name' => 'admin',
                'address' => 'Pasig',
                'contact_number' => '99999999',
                'email' => 'kim@gmail.com',
                'password' => Hash::make('user'),
                'role_id' => 2, 
            ],
        ]);
    }
}
