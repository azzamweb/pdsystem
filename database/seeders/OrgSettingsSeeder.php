<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrgSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('org_settings')->updateOrInsert(
            ['singleton' => true],
            [
                'name' => 'Badan Pengelola Keuangan dan Aset Daerah',
                'short_name' => 'BPKAD',
                'address' => 'Jl. Contoh No. 1, Bengkalis',
                'city' => 'Bengkalis',
                'province' => 'Riau',
                'phone' => '0766-123456',
                'email' => 'bpkad@example.go.id',
                'website' => 'https://bpkad.example.go.id',
                'head_user_id' => null, // nanti di-set setelah ada user kepala
                'head_title' => 'Kepala Badan',
                'signature_path' => null,
                'stamp_path' => null,
                'settings' => json_encode([
                    'ym_separator' => '/',
                    'qr_footer_text' => 'Verifikasi keaslian dokumen via QR.',
                    'letterhead' => [
                        'show_left_logo' => true,
                        'show_right_logo' => false,
                    ],
                ]),
                'singleton' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
