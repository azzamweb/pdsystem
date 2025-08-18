<?php

namespace Database\Seeders;

use App\Models\DocNumberFormat;
use Illuminate\Database\Seeder;

class DocNumberFormatSeeder extends Seeder
{
    public function run(): void
    {
        DocNumberFormat::updateOrCreate([
            'doc_type' => 'ND',
            'unit_scope_id' => null,
        ], [
            'format_string' => '{seq}/ND/{unit_code}/{roman_month}/{year}',
            'doc_code' => 'ND',
            'reset_policy' => 'YEARLY',
            'padding' => 3,
            'is_active' => 1,
            'notes' => 'Format ND global',
        ]);
    }
}
