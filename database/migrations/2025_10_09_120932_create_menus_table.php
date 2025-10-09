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
        Schema::create('menus', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->integer('sort_number')->default(0);
            $table->string('menu_name'); // Name of the menu
            $table->string('language', 10); // Language code like 'en', 'hi', 'fr'
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes(); // deleted_at column for soft deletes

            // Optional: prevent duplicate menu_name per language
            $table->unique(['menu_name', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
