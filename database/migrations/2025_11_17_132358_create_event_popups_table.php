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
         Schema::create('event_popups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('imgurl');
            $table->unsignedTinyInteger('date');   // day of month
            $table->unsignedTinyInteger('month');  // month number
            $table->text('language');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_popups');
    }
};
