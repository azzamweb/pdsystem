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
        Schema::create('representative_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->foreignId('travel_grade_id')->constrained()->onDelete('cascade');
            $table->decimal('rate_amount', 12, 2); // Tarif representasi
            $table->string('description')->nullable(); // Keterangan tambahan
            $table->date('valid_from'); // Tanggal mulai berlaku
            $table->date('valid_to')->nullable(); // Tanggal berakhir berlaku
            $table->string('source_ref')->nullable(); // Sumber referensi
            $table->timestamps();

            // Unique constraint untuk kombinasi province, travel_grade, dan periode
            $table->unique(['province_id', 'travel_grade_id', 'valid_from'], 'unique_representative_rate_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representative_rates');
    }
};
