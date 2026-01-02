<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MigrateWordpressUsers extends Command
{
    protected $signature = 'migrate:wordpress-users';
    protected $description = 'Migrate users from WordPress to Laravel using direct env connection';

    public function handle(): int
    {
        $this->info('Starting WordPress → Laravel user migration...');

        // Connect directly using env variables
        $wpUsers = DB::connection('wordpress')->table('users')->get();

        $total = $wpUsers->count();
        //dd($wpUsers);

        if ($total === 0) {
            $this->info('No WordPress users found.');
            return 0;
        }

        $this->info("Found {$total} users. Migrating...");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($wpUsers as $wpUser) {
            if ($wpUser->user_email === 'manishkumar@ibarts.in') {
                DB::table('users')->updateOrInsert(
                    ['email' => $wpUser->user_email],
                    [
                        'name' => $wpUser->display_name,
                        'email' => $wpUser->user_email,
                        'password' => Hash::make('manishkumar@ibarts.in'),
                        'language' => 'english',
                        'role' => 'admin',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } else {
                DB::table('users')->updateOrInsert(
                    ['email' => $wpUser->user_email],
                    [
                        'name' => $wpUser->display_name,
                        'email' => $wpUser->user_email,
                        'password' => Hash::make('password'),
                        'language' => 'english',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('✅ Migration completed successfully!');

        return 0;
    }
}
