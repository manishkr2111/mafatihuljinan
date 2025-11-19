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
            $table->id();
            $table->integer('sort_number')->default(0);
            $table->string('menu_name'); 
            $table->string('post_type'); 
            $table->string('language', 10);
            $table->dateTime('last_data_updated_at')->nullable();
            $table->timestamps(); 
            $table->softDeletes();

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
