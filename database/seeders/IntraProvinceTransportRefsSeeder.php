<?php

namespace Database\Seeders;

use App\Models\IntraProvinceTransportRef;
use App\Models\OrgPlace;
use App\Models\City;
use Illuminate\Database\Seeder;

class IntraProvinceTransportRefsSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil org places yang ada
        $orgPlaces = OrgPlace::all();
        
        // Ambil kota-kota di Provinsi Riau (kemendagri_code dimulai dengan 14)
        $riauCities = City::whereHas('province', function($query) {
            $query->where('kemendagri_code', 'LIKE', '14%');
        })->get();

        if ($orgPlaces->isEmpty() || $riauCities->isEmpty()) {
            return; // Tidak ada data yang cukup
        }

        // Base prices untuk transportasi dalam provinsi (dalam ribuan rupiah)
        $basePrices = [
            'short' => 50, // Jarak pendek (< 50 km)
            'medium' => 100, // Jarak menengah (50-100 km)
            'long' => 150, // Jarak jauh (> 100 km)
        ];

        // Simulasi jarak berdasarkan nama kota
        $distanceMultipliers = [
            'Pekanbaru' => 1.0, // Base
            'Dumai' => 1.8, // Lebih jauh
            'Bengkalis' => 1.5, // Cukup jauh
            'Rengat' => 1.3, // Agak jauh
            'Bangkinang' => 1.2, // Sedikit jauh
            'Pasir Pangaraian' => 1.4, // Cukup jauh
            'Siak Sri Indrapura' => 1.1, // Dekat
            'Teluk Kuantan' => 1.6, // Jauh
            'Ujung Tanjung' => 1.7, // Sangat jauh
        ];

        foreach ($orgPlaces as $orgPlace) {
            foreach ($riauCities as $city) {
                // Skip jika origin dan destination sama
                if ($orgPlace->city_id === $city->id) {
                    continue;
                }

                // Tentukan base price berdasarkan jarak
                $cityName = $city->name;
                $multiplier = 1.0;
                
                foreach ($distanceMultipliers as $key => $value) {
                    if (str_contains(strtolower($cityName), strtolower($key))) {
                        $multiplier = $value;
                        break;
                    }
                }

                // Tentukan base price berdasarkan multiplier
                if ($multiplier <= 1.2) {
                    $basePrice = $basePrices['short'];
                } elseif ($multiplier <= 1.5) {
                    $basePrice = $basePrices['medium'];
                } else {
                    $basePrice = $basePrices['long'];
                }

                $finalPrice = $basePrice * $multiplier * 1000; // Konversi ke rupiah

                // Buat beberapa periode validitas untuk testing
                $validFrom = now()->subMonths(rand(0, 6))->startOfMonth();
                $validTo = rand(0, 1) ? $validFrom->copy()->addMonths(rand(6, 24)) : null;

                IntraProvinceTransportRef::create([
                    'origin_place_id' => $orgPlace->id,
                    'destination_city_id' => $city->id,
                    'pp_amount' => $finalPrice,
                    'valid_from' => $validFrom,
                    'valid_to' => $validTo,
                    'source_ref' => 'SK Bupati/Walikota No. ' . rand(100, 999) . '/' . rand(1, 12) . '/' . date('Y'),
                ]);
            }
        }
    }
}
