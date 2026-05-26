<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('job_alert_preferences')) {
            Schema::create('job_alert_preferences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('name')->nullable();
                $table->string('keywords')->nullable();
                $table->string('location')->nullable();
                $table->string('category')->nullable();
                $table->string('remote_type')->nullable();
                $table->string('employment_type')->nullable();
                $table->enum('frequency', ['daily', 'weekly'])->default('daily');
                $table->boolean('email_enabled')->default(true);
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_sent_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_alert_preferences');
    }
};
