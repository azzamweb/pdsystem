<?php

namespace Database\Seeders;

use App\Models\IntraDistrictTransportRef;
use App\Models\OrgPlace;
use App\Models\District;
use Illuminate\Database\Seeder;

class IntraDistrictTransportRefsSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil org places yang ada
        $orgPlaces = OrgPlace::all();
        
        // Ambil semua kecamatan yang ada
        $districts = District::all();

        if ($orgPlaces->isEmpty() || $districts->isEmpty()) {
            return; // Tidak ada data yang cukup
        }

        // Base prices untuk transportasi dalam kecamatan (dalam ribuan rupiah)
        $basePrices = [
            'very_short' => 15, // Jarak sangat pendek (< 10 km)
            'short' => 25, // Jarak pendek (10-25 km)
            'medium' => 35, // Jarak menengah (25-50 km)
            'long' => 50, // Jarak jauh (> 50 km)
        ];

        // Simulasi jarak berdasarkan nama kecamatan
        $distanceMultipliers = [
            'Pekanbaru Kota' => 1.0, // Base
            'Pekanbaru Selatan' => 1.2, // Sedikit jauh
            'Pekanbaru Barat' => 1.1, // Dekat
            'Pekanbaru Timur' => 1.3, // Agak jauh
            'Pekanbaru Utara' => 1.4, // Cukup jauh
            'Senapelan' => 1.0, // Base
            'Rumbai' => 1.5, // Jauh
            'Rumbai Pesisir' => 1.6, // Sangat jauh
            'Bukit Raya' => 1.2, // Sedikit jauh
            'Sail' => 1.1, // Dekat
            'Lima Puluh' => 1.3, // Agak jauh
            'Payung Sekaki' => 1.4, // Cukup jauh
            'Marpoyan Damai' => 1.2, // Sedikit jauh
            'Tenayan Raya' => 1.5, // Jauh
            'Kulim' => 1.6, // Sangat jauh
        ];

        foreach ($orgPlaces as $orgPlace) {
            foreach ($districts as $district) {
                // Skip jika origin dan destination sama (dalam kecamatan yang sama)
                if ($orgPlace->district_id === $district->id) {
                    continue;
                }

                // Tentukan base price berdasarkan jarak
                $districtName = $district->name;
                $multiplier = 1.0;
                
                foreach ($distanceMultipliers as $key => $value) {
                    if (str_contains(strtolower($districtName), strtolower($key))) {
                        $multiplier = $value;
                        break;
                    }
                }

                // Tentukan base price berdasarkan multiplier
                if ($multiplier <= 1.1) {
                    $basePrice = $basePrices['very_short'];
                } elseif ($multiplier <= 1.3) {
                    $basePrice = $basePrices['short'];
                } elseif ($multiplier <= 1.5) {
                    $basePrice = $basePrices['medium'];
                } else {
                    $basePrice = $basePrices['long'];
                }

                $finalPrice = $basePrice * $multiplier * 1000; // Konversi ke rupiah

                IntraDistrictTransportRef::create([
                    'origin_place_id' => $orgPlace->id,
                    'destination_district_id' => $district->id,
                    'pp_amount' => $finalPrice,
                ]);
            }
        }
    }
}
