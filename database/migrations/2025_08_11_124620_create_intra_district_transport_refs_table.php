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
        Schema::create('intra_district_transport_refs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_place_id')->constrained('org_places')->cascadeOnDelete();
            $table->foreignId('destination_district_id')->constrained('districts')->cascadeOnDelete();
            $table->decimal('pp_amount', 12, 2); // Per person amount untuk transportasi dalam kecamatan
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('origin_place_id');
            $table->index('destination_district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intra_district_transport_refs');
    }
};
