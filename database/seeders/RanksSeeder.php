<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['code' => 'I/a',   'name' => 'Juru Muda'],
            ['code' => 'I/b',   'name' => 'Juru Muda Tingkat I'],
            ['code' => 'I/c',   'name' => 'Juru'],
            ['code' => 'I/d',   'name' => 'Juru Tingkat I'],
            ['code' => 'II/a',  'name' => 'Pengatur Muda'],
            ['code' => 'II/b',  'name' => 'Pengatur Muda Tingkat I'],
            ['code' => 'II/c',  'name' => 'Pengatur'],
            ['code' => 'II/d',  'name' => 'Pengatur Tingkat I'],
            ['code' => 'III/a', 'name' => 'Penata Muda'],
            ['code' => 'III/b', 'name' => 'Penata Muda Tingkat I'],
            ['code' => 'III/c', 'name' => 'Penata'],
            ['code' => 'III/d', 'name' => 'Penata Tingkat I'],
            ['code' => 'IV/a',  'name' => 'Pembina'],
            ['code' => 'IV/b',  'name' => 'Pembina Tingkat I'],
            ['code' => 'IV/c',  'name' => 'Pembina Utama Muda'],
            ['code' => 'IV/d',  'name' => 'Pembina Utama Madya'],
            ['code' => 'IV/e',  'name' => 'Pembina Utama'],
        ];

        DB::table('ranks')->insert($data);
    }
}
