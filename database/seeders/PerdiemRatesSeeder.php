<?php

namespace Database\Seeders;

use App\Models\PerdiemRate;
use App\Models\Province;
use App\Models\TravelGrade;
use Illuminate\Database\Seeder;

class PerdiemRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = Province::all();
        $travelGrades = TravelGrade::all();

        // Tarif dasar per tingkatan (dalam ribuan rupiah)
        $baseRates = [
            'BUP' => [
                'luar_kota' => 1500,
                'dalam_kota_gt8h' => 750,
                'diklat' => 1000,
            ],
            'ESELON2_ANGGOTA_DPRD' => [
                'luar_kota' => 1200,
                'dalam_kota_gt8h' => 600,
                'diklat' => 800,
            ],
            'ESELON3_GOL4' => [
                'luar_kota' => 1000,
                'dalam_kota_gt8h' => 500,
                'diklat' => 700,
            ],
            'ESELON4_GOL3_2_1_PPPK_NONASN' => [
                'luar_kota' => 800,
                'dalam_kota_gt8h' => 400,
                'diklat' => 600,
            ],
        ];

        foreach ($provinces as $province) {
            foreach ($travelGrades as $travelGrade) {
                $baseRate = $baseRates[$travelGrade->code];
                
                // Modifikasi tarif berdasarkan provinsi (DKI Jakarta lebih tinggi)
                $multiplier = $province->kemendagri_code === '31' ? 1.2 : 1.0;
                
                PerdiemRate::create([
                    'province_id' => $province->id,
                    'travel_grade_id' => $travelGrade->id,
                    'satuan' => 'OH',
                    'luar_kota' => $baseRate['luar_kota'] * $multiplier * 1000, // Konversi ke rupiah
                    'dalam_kota_gt8h' => $baseRate['dalam_kota_gt8h'] * $multiplier * 1000,
                    'diklat' => $baseRate['diklat'] * $multiplier * 1000,
                ]);
            }
        }
    }
}
