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
            'english_surah',
            'english_dua',
            'english_daily_dua',
            'english_amaal_namaz',
            'english_burial_acts_prayers',
            'english_amaal',
            'english_essential_supplications',
            'english_munajat',
            'english_salaat_namaz',
            'english_salwaat',
            'english_sahifas_ahlulbayt',
            'english_tasbih',
            'english_travel_ziyarat',
            'english_ziyarat',

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
            'gujarati_sahifas_ahlulbayt',
            'gujarati_tasbih',
            'gujarati_travel_ziyarat',
            'gujarati_ziyarat',


            'hindi_surah',
            'hindi_dua',
            'hindi_daily_dua',
            'hindi_amaal_namaz',
            'hindi_burial_acts_prayers',
            'hindi_amaal',
            'hindi_essential_supplications',
            'hindi_munajat',
            'hindi_salaat_namaz',
            'hindi_salwaat',
            'hindi_sahifas_ahlulbayt',
            'hindi_tasbih',
            'hindi_travel_ziyarat',
            'hindi_ziyarat',

            'urdu_surah',
            'urdu_dua',
            'urdu_daily_dua',
            'urdu_amaal_namaz',
            'urdu_burial_acts_prayers',
            'urdu_amaal',
            'urdu_essential_supplications',
            'urdu_munajat',
            'urdu_salaat_namaz',
            'urdu_salwaat',
            'urdu_sahifas_ahlulbayt',
            'urdu_tasbih',
            'urdu_travel_ziyarat',
            'urdu_ziyarat',

            'roman_Urdu_surah',
            'roman_Urdu_dua',
            'roman_Urdu_daily_dua',
            'roman_Urdu_amaal_namaz',
            'roman_Urdu_burial_acts_prayers',
            'roman_Urdu_amaal',
            'roman_Urdu_essential_supplications',
            'roman_Urdu_munajat',
            'roman_Urdu_salaat_namaz',
            'roman_Urdu_salwaat',
            'roman_Urdu_sahifas_ahlulbayt',
            'roman_Urdu_tasbih',
            'roman_Urdu_travel_ziyarat',
            'roman_Urdu_ziyarat',

            'french_surah',
            'french_dua',
            'french_daily_dua',
            'french_amaal_namaz',
            'french_burial_acts_prayers',
            'french_amaal',
            'french_essential_supplications',
            'french_munajat',
            'french_salaat_namaz',
            'french_salwaat',
            'french_sahifas_ahlulbayt',
            'french_tasbih',
            'french_travel_ziyarat',
            'french_ziyarat',

            'swahili_surah',
            'swahili_dua',
            'swahili_daily_dua',
            'swahili_amaal_namaz',
            'swahili_burial_acts_prayers',
            'swahili_amaal',
            'swahili_essential_supplications',
            'swahili_munajat',
            'swahili_salaat_namaz',
            'swahili_salwaat',
            'swahili_sahifas_ahlulbayt',
            'swahili_tasbih',
            'swahili_travel_ziyarat',
            'swahili_ziyarat',
        ];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('redirect_deeplink_post_type')->after('redirect_deep_link')->nullable();
                $table->longText('significance_content')->after('simple_translation')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'english_surah',
            'english_dua',
            'english_daily_dua',
            'english_amaal_namaz',
            'english_burial_acts_prayers',
            'english_amaal',
            'english_essential_supplications',
            'english_munajat',
            'english_salaat_namaz',
            'english_salwaat',
            'english_sahifas_ahlulbayt',
            'english_tasbih',
            'english_travel_ziyarat',
            'english_ziyarat',

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
            'gujarati_sahifas_ahlulbayt',
            'gujarati_tasbih',
            'gujarati_travel_ziyarat',
            'gujarati_ziyarat',


            'hindi_surah',
            'hindi_dua',
            'hindi_daily_dua',
            'hindi_amaal_namaz',
            'hindi_burial_acts_prayers',
            'hindi_amaal',
            'hindi_essential_supplications',
            'hindi_munajat',
            'hindi_salaat_namaz',
            'hindi_salwaat',
            'hindi_sahifas_ahlulbayt',
            'hindi_tasbih',
            'hindi_travel_ziyarat',
            'hindi_ziyarat',

            'urdu_surah',
            'urdu_dua',
            'urdu_daily_dua',
            'urdu_amaal_namaz',
            'urdu_burial_acts_prayers',
            'urdu_amaal',
            'urdu_essential_supplications',
            'urdu_munajat',
            'urdu_salaat_namaz',
            'urdu_salwaat',
            'urdu_sahifas_ahlulbayt',
            'urdu_tasbih',
            'urdu_travel_ziyarat',
            'urdu_ziyarat',

            'roman_Urdu_surah',
            'roman_Urdu_dua',
            'roman_Urdu_daily_dua',
            'roman_Urdu_amaal_namaz',
            'roman_Urdu_burial_acts_prayers',
            'roman_Urdu_amaal',
            'roman_Urdu_essential_supplications',
            'roman_Urdu_munajat',
            'roman_Urdu_salaat_namaz',
            'roman_Urdu_salwaat',
            'roman_Urdu_sahifas_ahlulbayt',
            'roman_Urdu_tasbih',
            'roman_Urdu_travel_ziyarat',
            'roman_Urdu_ziyarat',

            'french_surah',
            'french_dua',
            'french_daily_dua',
            'french_amaal_namaz',
            'french_burial_acts_prayers',
            'french_amaal',
            'french_essential_supplications',
            'french_munajat',
            'french_salaat_namaz',
            'french_salwaat',
            'french_sahifas_ahlulbayt',
            'french_tasbih',
            'french_travel_ziyarat',
            'french_ziyarat',

            'swahili_surah',
            'swahili_dua',
            'swahili_daily_dua',
            'swahili_amaal_namaz',
            'swahili_burial_acts_prayers',
            'swahili_amaal',
            'swahili_essential_supplications',
            'swahili_munajat',
            'swahili_salaat_namaz',
            'swahili_salwaat',
            'swahili_sahifas_ahlulbayt',
            'swahili_tasbih',
            'swahili_travel_ziyarat',
            'swahili_ziyarat',
        ];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('redirect_deeplink_post_type');
                $table->dropColumn('significance_content');
            });
        }
    }
};
