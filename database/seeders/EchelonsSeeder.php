<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EchelonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['code' => 'I.a',  'name' => 'Eselon I.a'],
            ['code' => 'I.b',  'name' => 'Eselon I.b'],
            ['code' => 'II.a', 'name' => 'Eselon II.a'],
            ['code' => 'II.b', 'name' => 'Eselon II.b'],
            ['code' => 'III.a','name' => 'Eselon III.a'],
            ['code' => 'III.b','name' => 'Eselon III.b'],
            ['code' => 'IV.a', 'name' => 'Eselon IV.a'],
            ['code' => 'IV.b', 'name' => 'Eselon IV.b'],
        ];

        DB::table('echelons')->insert($data);
    }
}
