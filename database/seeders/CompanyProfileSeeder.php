<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_profiles')->insert([
            [
                'user_id' => '3',
                'name' => 'PT Digital Komputindo',
                'description' => 'Perusahaan bergerak dibidang konsultasi IT',
                'city' => "Kota Bandung",
                'province' => "Jawa Barat",
            ],
            [
                'user_id' => '4',
                'name' => 'Kedai Kopi',
                'description' => 'Menyediakan berbagai jenis kopi pilihan',
                'city' => "Kota Bogor",
                'province' => "Jawa Barat",
            ],
        ]);
    }
}
