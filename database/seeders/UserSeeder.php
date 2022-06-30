<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'user1',
                'email' => 'user@gmail.com',
                'password' => Hash::make('user123'),
                'role' => 'USER'
            ],
            [
                'username' => 'user2',
                'email' => 'user2@gmail.com',
                'password' => Hash::make('test123'),
                'role' => 'USER'
            ],
            [
                'username' => 'company1',
                'email' => 'company@gmail.com',
                'password' => Hash::make('company123'),
                'role' => 'COMPANY'
            ],
            [
                'username' => 'company2',
                'email' => 'company2@gmail.com',
                'password' => Hash::make('test123'),
                'role' => 'COMPANY'
            ],
            [
                'username' => 'admin1',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'ADMIN'
            ]
        ]);
    }
}
