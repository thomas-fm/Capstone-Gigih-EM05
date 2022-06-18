<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_profiles')->insert([
            [
                'user_id' => '1',
                'fullname' => 'User 1',
                'description' => "I'm a programmer",
                'city' => "Kota Bandung",
                'province' => "Jawa Barat",
                'email' => "user@gmail.com",
                'phone' => "0812345678"
            ],
            [
                'user_id' => '2',
                'fullname' => 'User 2',
                'description' => "I have experienced as a barista",
                'city' => "Kota Semarang",
                'province' => "Jawa Tengah",
                'email' => "user2@gmail.com",
                'phone' => "0812345698"
            ]
        ]);
    }
}
