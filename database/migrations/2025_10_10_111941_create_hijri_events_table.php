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
        Schema::create('hijri_events', function (Blueprint $table) {
            $table->id();
            $table->integer('date'); // 1-30
            $table->string('month'); // Muharram, Safar, etc
            $table->string('event'); // Event name
            $table->string('language')->default('english');
            $table->string('text_color')->default('Black');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hijri_events');
    }
};
