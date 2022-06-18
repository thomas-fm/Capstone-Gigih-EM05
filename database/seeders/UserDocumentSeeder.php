<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_documents')->insert([
            [
                'user_profile_id' => '1',
                'type' => 'CV',
                'url' => "https://github.com/thomas-fm/Capstone-Gigih-EM05",
            ],
            [
                'user_profile_id' => '1',
                'type' => 'CERTIFICATE',
                'url' => "https://github.com/thomas-fm/Capstone-Gigih-EM05",
            ],
            [
                'user_profile_id' => '2',
                'type' => 'CERTIFICATE',
                'url' => "https://github.com/thomas-fm/Capstone-Gigih-EM05",
            ],
        ]);
    }
}
