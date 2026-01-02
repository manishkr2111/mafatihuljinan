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
        $postTables = [
            'english_surah',
            'gujarati_surah',
            'hindi_surah',
            'urdu_surah',
            'roman_Urdu_surah',
            'french_surah',
            'swahili_surah',
        ];
        foreach ($postTables as $postTable) {
            Schema::table($postTable, function (Blueprint $table) {
                $table->json('word_meanings')->nullable()->after('simple_translation');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $postTables = [
            'english_surah',
            'gujarati_surah',
            'hindi_surah',
            'urdu_surah',
            'roman_Urdu_surah',
            'french_surah',
            'swahili_surah',
        ];
        foreach ($postTables as $postTable) {
            Schema::table($postTable, function (Blueprint $table) {
                $table->dropColumn('word_meanings');
            });
        }
    }
};
