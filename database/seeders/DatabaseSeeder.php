<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Existing seeders
            UnitsSeeder::class,
            PositionsSeeder::class,
            RanksSeeder::class,
            EchelonsSeeder::class,
            OrgSettingsSeeder::class,
            
            // Location seeders
            ProvincesSeeder::class,
            CitiesSeeder::class,
            DistrictsSeeder::class,
            OrgPlacesSeeder::class,
            
            // Transport & Route seeders
            TransportModesSeeder::class,
            TravelRoutesSeeder::class,
            
            // Travel Grade seeders
            TravelGradesSeeder::class,
            
            // Perdiem Rate seeders
            PerdiemRatesSeeder::class,
            
            // Lodging Cap seeders
            LodgingCapsSeeder::class,
            
            // Representation Rate seeders
            RepresentationRatesSeeder::class,
            
            // Airfare Reference seeders
            AirfareRefsSeeder::class,
            
            // Intra Province Transport Reference seeders
            IntraProvinceTransportRefsSeeder::class,
            
            // Intra District Transport Reference seeders
            IntraDistrictTransportRefsSeeder::class,
        ]);
    }
}
