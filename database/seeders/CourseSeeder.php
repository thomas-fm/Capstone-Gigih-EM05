<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert([
            [
                'category_id' => 1,
                'title' => 'Laravel fundamental for beginner',
                'description' => 'Kuasai laravel dalam 10 hari',
                'difficulty' => 'BEGINNER',
                'price' => 0,
            ],
            [
                'category_id' => 2,
                'title' => 'Adobe Photoshop Course',
                'description' => 'Kuasai photoshop dalam 10 hari dengan materi dari profesional',
                'difficulty' => 'INTERMEDIATE',
                'price' => 200000,
            ],
            [
                'category_id' => 3,
                'title' => 'Learn Ruby on Rails on 30 day',
                'description' => 'Materi dari technical lead Gojek',
                'difficulty' => 'ADVANCE',
                'price' => 0,
            ],
        ]);
    }
}
