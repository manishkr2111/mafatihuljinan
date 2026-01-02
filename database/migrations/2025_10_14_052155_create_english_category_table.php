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
        Schema::create('english_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('wordpress_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('deeplink_url')->nullable();
            $table->string('post_type')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_number')->default(0);
            $table->foreignId('parent_id')->nullable()->constrained('english_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_categories');
    }
};
