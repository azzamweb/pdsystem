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
        Schema::table('trip_reports', function (Blueprint $table) {
            if (Schema::hasColumn('trip_reports', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('trip_reports', 'status')) {
                $table->enum('status', ['DRAFT','SUBMITTED','APPROVED'])->default('DRAFT');
            }
        });
    }
};
