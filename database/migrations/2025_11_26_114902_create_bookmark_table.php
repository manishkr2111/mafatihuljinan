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
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->unsignedBigInteger('post_id');
            $table->json('bookmark_indexes')->nullable();
            $table->json('indexes_arabic')->nullable();
            $table->json('indexes_transliteration')->nullable();
            $table->json('indexes_translation')->nullable();
            $table->string('post_type');
            $table->string('language', 10);
            $table->timestamps();
            $table->unique(['user_id', 'post_id', 'post_type', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
