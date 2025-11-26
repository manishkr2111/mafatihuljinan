<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateWordpressCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-wordpress-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate WordPress taxonomy categories to Laravel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration for WordPress categories...');

        // Fetch all terms for your custom taxonomy
        $wpCategories = DB::connection('wordpress')->table('terms as t')
            ->join('term_taxonomy as tt', 't.term_id', '=', 'tt.term_id')
            ->where('tt.taxonomy', 'dua-day-gujarati') // your taxonomy name
            ->select('t.term_id', 't.name', 't.slug', 'tt.description', 'tt.parent')
            ->get();

        $total = $wpCategories->count();
        if ($total === 0) {
            $this->warn('No categories found in WordPress.');
            return 0;
        }

        $this->info("Total categories found: {$total}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        // Keep track of Laravel IDs for parent mapping
        $termMap = []; // WP term_id => Laravel ID

        foreach ($wpCategories as $wpCat) {
            $parentId = $wpCat->parent ? ($termMap[$wpCat->parent] ?? null) : null;
			$baseSlug = Str::slug($wpCat->name);
			$slug = 'daily-dua-'.$baseSlug;
			$counter = 1;
			while (
				DB::table('gujarati_categories')
					->where('slug', $slug)
					->where('wordpress_id', '!=', $wpCat->term_id)
					->exists()
			) {
				$slug = $baseSlug . '-' . $counter++;
			}
            $categoryId = DB::table('gujarati_categories')->updateOrInsert(
                ['wordpress_id' => $wpCat->term_id], // make sure you have this column
                [
                    'name' => $wpCat->name,
                    'slug' => $slug,
                    'description' => $wpCat->description,
                    'parent_id' => $parentId,
                    'sort_number' => 0,
                    'post_type' => 'daily-dua', // optional
                ]
            );

            // Get the Laravel ID of inserted/updated category
            $laravelCat = DB::table('gujarati_categories')->where('wordpress_id', $wpCat->term_id)->first();
            $termMap[$wpCat->term_id] = $laravelCat->id;

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('✅ Categories migrated successfully!');
        return 0;
    }
}
