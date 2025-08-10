<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['code' => 'BPKAD',       'name' => 'Badan Pengelola Keuangan dan Aset Daerah', 'parent_id' => null],
            ['code' => 'BPKAD-KEU',   'name' => 'Bidang Perbendaharaan', 'parent_id' => 1],
            ['code' => 'BPKAD-ANG',   'name' => 'Bidang Anggaran', 'parent_id' => 1],
            ['code' => 'BPKAD-ASET',  'name' => 'Bidang Aset', 'parent_id' => 1],
        ];

        DB::table('units')->insert($data);
    }
}
