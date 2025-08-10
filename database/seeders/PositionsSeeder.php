<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Kepala Dinas', 'type' => 'Struktural'],
            ['name' => 'Sekretaris', 'type' => 'Struktural'],
            ['name' => 'Kepala Bidang', 'type' => 'Struktural'],
            ['name' => 'Kepala Seksi', 'type' => 'Struktural'],
            ['name' => 'Bendahara', 'type' => 'Fungsional'],
            ['name' => 'Staf', 'type' => 'Fungsional'],
        ];

        DB::table('positions')->insert($data);
    }
}
