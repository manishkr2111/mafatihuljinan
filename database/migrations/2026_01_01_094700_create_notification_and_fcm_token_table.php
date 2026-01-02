<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_fcm_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('language')->default('english');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('fcm_token')->unique();

            $table->string('device_type')->nullable(); // android, ios, web
            $table->string('device_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });

        Schema::create('notification_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('language')->default('english');
            $table->string('title');
            $table->text('message');
            $table->string('image_url')->nullable();
            $table->enum('frequency', [
                'daily',
                'weekly',
                'monthly',
                'yearly',
                'custom'
            ]);
            // Schedule details
            $table->unsignedTinyInteger('send_hour');   // 0–23
            $table->unsignedTinyInteger('send_minute'); // 0–59
            // weekly → Mon, Tue, etc.
            $table->string('day_of_week')->nullable();
            // monthly → 1–31
            $table->unsignedTinyInteger('day_of_month')->nullable();
            // yearly → 1–12
            $table->unsignedTinyInteger('month_of_year')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'send_hour', 'send_minute']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_schedules');
        Schema::dropIfExists('user_fcm_tokens');
    }
};
