<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['kemendagri_code' => '14', 'name' => 'RIAU'],
            ['kemendagri_code' => '31', 'name' => 'DKI JAKARTA'],
            ['kemendagri_code' => '32', 'name' => 'JAWA BARAT'],
            ['kemendagri_code' => '33', 'name' => 'JAWA TENGAH'],
            ['kemendagri_code' => '34', 'name' => 'DI YOGYAKARTA'],
            ['kemendagri_code' => '35', 'name' => 'JAWA TIMUR'],
            ['kemendagri_code' => '36', 'name' => 'BANTEN'],
            ['kemendagri_code' => '51', 'name' => 'BALI'],
            ['kemendagri_code' => '52', 'name' => 'NUSA TENGGARA BARAT'],
            ['kemendagri_code' => '53', 'name' => 'NUSA TENGGARA TIMUR'],
        ];

        foreach ($provinces as $province) {
            Province::create($province);
        }
    }
}
