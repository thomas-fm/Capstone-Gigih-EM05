<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jobs')->insert([
            [
                'company_profile_id' => 1,
                'position' => 'Principal Software Engineer',
                'type' => 'FULLTIME',
                'description' => 'Min 2+ year experience with Laravel',
                'isRemote' => false,
                'city' => 'Kota Semarang',
                'province' => 'Jawa Tengah',
                'duration' => null,
                'minSalary' => '6000000',
                'maxSalary' => '12000000',
                'expired' => Carbon::createFromFormat('Y-m-d', '2023-05-21'),
                'active' => true,
                'courseRequirement' => false
            ],
            [
                'company_profile_id' => 1,
                'position' => 'Freelance Software Engineer',
                'type' => 'FULLTIME',
                'description' => 'Min 2+ year experience with Ruby on Rails. ',
                'isRemote' => false,
                'city' => 'Kota Semarang',
                'province' => 'Jawa Tengah',
                'duration' => null,
                'minSalary' => '6000000',
                'maxSalary' => '12000000',
                'expired' => Carbon::createFromFormat('Y-m-d', '2023-05-21'),
                'active' => true,
                'courseRequirement' => true
            ],
        ]);
    }
}
