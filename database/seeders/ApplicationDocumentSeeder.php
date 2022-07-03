<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('application_documents')->insert([
            [
                'job_application_id' => 1,
                'user_document_id' => 3,
            ]
        ]);
    }
}
