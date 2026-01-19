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
        $tables = [
            'swahili_categories',
            'english_categories',
            'gujarati_categories',
            'hindi_categories',
            'urdu_categories',
            'roman_urdu_categories',
            'french_categories'
        ];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('popup_image')->after('description')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'swahili_categories',
            'english_categories',
            'gujarati_categories',
            'hindi_categories',
            'urdu_categories',
            'roman_Urdu_categories',
            'french_categories'
        ];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('popup_image');
            });
        }
    }
};
