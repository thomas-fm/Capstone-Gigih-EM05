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
                'name' => Str::random(10),
                'email' => 'user@gmail.com',
                'password' => Hash::make('user123'),
                'role' => 'USER'
            ],
            [
                'name' => Str::random(10),
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'ADMIN'
            ],
            [
                'name' => Str::random(10),
                'email' => 'company@gmail.com',
                'password' => Hash::make('company123'),
                'role' => 'COMPANY'
            ],
        ]);
    }
}
