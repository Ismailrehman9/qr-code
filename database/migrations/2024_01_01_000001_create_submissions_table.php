<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('seat_qr_id', 10)->index();
            $table->string('name');
            $table->string('phone', 20)->unique()->index();
            $table->string('email');
            $table->date('date_of_birth');
            $table->boolean('whatsapp_optin')->default(false);
            $table->text('joke')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
