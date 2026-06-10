<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropUnique('qr_codes_seat_number_unique');
            $table->unique(['generated_for_date', 'seat_number'], 'qr_codes_generated_for_date_seat_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropUnique('qr_codes_generated_for_date_seat_number_unique');
            $table->unique('seat_number');
        });
    }
};
