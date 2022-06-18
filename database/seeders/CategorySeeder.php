<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [ "name" => 'Information Technology' ],
            [ "name" => 'Graphic Design' ],
            [ "name" => 'Finance' ],
            [ "name" => 'Human Resources' ],
            [ "name" => 'Analyst' ],
            [ "name" => 'Content Writer' ],
            [ "name" => 'Education' ],
            [ "name" => 'Sales & Marketing' ],
            [ "name" => 'Driver' ],
            [ "name" => 'Gardener' ],
            [ "name" => 'English Tutor' ],
            [ "name" => 'Data Science' ],
            [ "name" => 'Translator' ],
        ]);
    }
}
