<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('intra_province_transport_refs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_place_id')->constrained('org_places')->cascadeOnDelete();
            $table->foreignId('destination_city_id')->constrained('cities')->cascadeOnDelete();
            $table->decimal('pp_amount', 12, 2); // Per person amount untuk transportasi dalam provinsi
            $table->date('valid_from'); // Tanggal mulai berlaku
            $table->date('valid_to')->nullable(); // Tanggal berakhir berlaku (nullable untuk yang masih berlaku)
            $table->string('source_ref', 255)->nullable(); // Referensi sumber (SK, peraturan, dll)
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('origin_place_id');
            $table->index('destination_city_id');
            $table->index('valid_from');
            $table->index('valid_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intra_province_transport_refs');
    }
};
