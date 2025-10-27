<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateWordpressPost extends Command
{
    protected $signature = 'app:migrate-wordpress-post';
    protected $description = 'Migrate Surah posts from WordPress to Laravel';

    public function handle()
    {
        $this->info('Starting migration for WordPress "surah" posts...');

        // Fetch Surah posts
        $wpSurahs = DB::connection('wordpress')->table('posts')
            ->where('post_type', 'surah-english')
            ->where('post_status', 'publish')
            ->get();

        $total = $wpSurahs->count();

        if ($total === 0) {
            $this->warn('No Surah posts found in WordPress.');
            return 0;
        }

        $this->info("Total Surah posts found: {$total}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($wpSurahs as $wpSurah) {
            // Get ACF/meta data for this post
            $meta = DB::connection('wordpress')->table('postmeta')
                ->where('post_id', $wpSurah->ID)
                ->pluck('meta_value', 'meta_key')
                ->toArray();

            // Fetch WordPress categories for this post
            $wpCategoryIds = DB::connection('wordpress')->table('term_relationships as tr')
                ->join('term_taxonomy as tt', 'tr.term_taxonomy_id', '=', 'tt.term_taxonomy_id')
                ->where('tr.object_id', $wpSurah->ID)
                ->where('tt.taxonomy', 'surah-english-category')
                ->pluck('tt.term_id')
                ->toArray();

            // Map WordPress category IDs to Laravel category IDs
            $laravelCategoryIds = [];
            if (!empty($wpCategoryIds)) {
                $laravelCategoryIds = DB::table('english_categories')
                    ->whereIn('wordpress_id', $wpCategoryIds)
                    ->pluck('id')
                    ->toArray();
            }
			
			$categoryIdsJson = !empty($laravelCategoryIds)
                ? json_encode(array_map('strval', $laravelCategoryIds))
                : null;

            // Insert/update post in Laravel
            DB::table('english_surah')->updateOrInsert(
                ['wordpress_id' => $wpSurah->ID],
                [
                    'title' => $wpSurah->post_title,
                    'slug' => $wpSurah->post_name,
                    'search_text' => $meta['search_text'] ?? null,
                    'redirect_deep_link' => $meta['redirect_url'] ?? null,
                    'roman_data' => $meta['roman_data'] ?? null,
                    'sort_number' => is_numeric($meta['sort_post_number']) ? (int)$meta['sort_post_number'] : null,
                    'arabic_islrc' => $meta['arabic_data_is_lrc'] ?? null,
                    'arabic_4line' => $meta['arabic_data_enable_4_line_text_arabic'] ?? 0,
                    'arabic_audio_url' => $meta['arabic_data_audio_url'] ?? null,
                    'arabic_content' => $meta['arabic_data_audio_content'] ?? null,
                    'simple_arabic' => $meta['simple_arabic_text'] ?? null,
                    'transliteration_islrc' => $meta['transliteration_data_is_lrc'] ?? null,
                    'transliteration_4line' => $meta['transliteration_data_enable_4_line_text_transliteration'] ?? 0,
                    'transliteration_audio_url' => $meta['transliteration_data_audio_url'] ?? null,
                    'transliteration_content' => $meta['transliteration_data_audio_content'] ?? null,
                    'simple_transliteration' => $meta['simple_translation_text_copy'] ?? null,
                    'translation_islrc' => $meta['translation_data_is_lrc'] ?? null,
                    'translation_4line' => $meta['translation_data_enable_4_line_text_translation'] ?? 0,
                    'translation_audio_url' => $meta['translation_data_audio_url'] ?? null,
                    'translation_content' => $meta['translation_data_audio_content'] ?? null,
                    'simple_translation' => $meta['simple_translation_text'] ?? null,
                    'next_post_title' => $meta['next_post_title'] ?? null,
                    'next_post_url' => $meta['next_post_url'] ?? null,
                    'internal_link' => $meta['internal_link'] ?? null,
                    'category_ids' => $categoryIdsJson,
                    'status' => 'published',
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('✅ Surah posts migrated successfully!');
        return 0;
    }
}
