<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enrollments')->insert([
            [
                'user_profile_id' => 1,
                'course_id' =>  1,
                'registration_id' => Str::orderedUuid(),
                'status' => 'TAKEN',
                'grade' => 0,
                'progress' => 50,
                'due' => Carbon::createFromFormat('Y-m-d', '2022-08-21'),
                'expired' => null
            ],
            [
                'user_profile_id' => 2,
                'course_id' =>  1,
                'registration_id' => Str::orderedUuid(),
                'status' => 'COMPLETED',
                'grade' => 96,
                'progress' => 100,
                'due' => Carbon::createFromFormat('Y-m-d', '2022-08-21'),
                'expired' => Carbon::createFromFormat('Y-m-d', '2025-08-21'),
            ],
        ]);
    }
}
