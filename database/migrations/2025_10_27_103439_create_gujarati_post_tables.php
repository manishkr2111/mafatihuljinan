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
            'gujarati_surah',
            'gujarati_dua',
            'gujarati_daily_dua',
            'gujarati_amaal_namaz',
            'gujarati_burial_acts_prayers',
            'gujarati_amaal',
            'gujarati_essential_supplications',
            'gujarati_munajat',
            'gujarati_salaat_namaz',
            'gujarati_salwaat',
            'gujarati_tasbih',
            'gujarati_travel_ziyarat',
            'gujarati_ziyarat',
        ];
        foreach ($tables as $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->integer('wordpress_id')->nullable();
                $table->string('title');
                $table->string('slug')->nullable();
                $table->text('search_text')->nullable();
                $table->string('redirect_deep_link')->nullable();
                $table->text('roman_data')->nullable();
                $table->integer('sort_number')->nullable();

                // Arabic
                $table->text('arabic_islrc')->nullable();
                $table->boolean('arabic_4line')->default(false);
                $table->string('arabic_audio_url')->nullable();
                $table->longText('arabic_content')->nullable();
                $table->longText('simple_arabic')->nullable();

                // Transliteration
                $table->text('transliteration_islrc')->nullable();
                $table->boolean('transliteration_4line')->default(false);
                $table->string('transliteration_audio_url')->nullable();
                $table->longText('transliteration_content')->nullable();
                $table->longText('simple_transliteration')->nullable();

                // Translation
                $table->text('translation_islrc')->nullable();
                $table->boolean('translation_4line')->default(false);
                $table->string('translation_audio_url')->nullable();
                $table->longText('translation_content')->nullable();
                $table->longText('simple_translation')->nullable();

                // Navigation & links
                $table->string('next_post_title')->nullable();
                $table->string('next_post_url')->nullable();
                $table->string('internal_link')->nullable();

                // Meta
                $table->json('category_ids')->nullable()->index();
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'gujarati_surah',
            'gujarati_dua',
            'gujarati_daily_dua',
            'gujarati_amaal_namaz',
            'gujarati_burial_acts_prayers',
            'gujarati_amaal',
            'gujarati_essential_supplications',
            'gujarati_munajat',
            'gujarati_salaat_namaz',
            'gujarati_salwaat',
            'gujarati_tasbih',
            'gujarati_travel_ziyarat',
            'gujarati_ziyarat',
        ];

        foreach ($tables as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }
};
