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
        Schema::table('sppd', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['user_id']);
            // Remove user_id field since 1 SPPD now represents all participants
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sppd', function (Blueprint $table) {
            // Add back user_id field if needed to rollback
            $table->unsignedBigInteger('user_id')->nullable()->after('spt_id');
            // Add back foreign key constraint
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
