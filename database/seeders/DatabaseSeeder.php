<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CompanyProfileSeeder::class,
            UserProfileSeeder::class,
            CategorySeeder::class,
            JobSeeder::class,
            JobCategorySeeder::class,
            CourseSeeder::class,
            EnrollmentSeeder::class,
            SkillAssesmentSeeder::class,
            UserDocumentSeeder::class,
            JobApplicationSeeder::class,
            ApplicationCourseSeeder::class,
            ApplicationDocumentSeeder::class
        ]);
    }
}
