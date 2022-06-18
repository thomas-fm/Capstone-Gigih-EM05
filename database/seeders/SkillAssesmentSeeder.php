<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillAssesmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skill_assesments')->insert([
            [
                'title' => 'Test Back End Engineer',
                'description' => 'Kerjakan soal hackerrank berikut dalam batas waktu 24 jam dan buat projek sederhana menggunakan laravel',
            ],
            [
                'title' => 'Video Pengenalan Diri',
                'description' => 'Buat video perkenalan dan ceritakan pengalamanmu yang berkaitan dengan pekerjaan ini',
            ],
        ]);
    }
}
