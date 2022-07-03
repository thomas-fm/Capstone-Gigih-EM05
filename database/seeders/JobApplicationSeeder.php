<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_applications')->insert([
            [
                'user_profile_id' => 2,
                'job_id' =>  1,
                'status' => 'ON_REVIEW',
            ]
        ]);
    }
}
