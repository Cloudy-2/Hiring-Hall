<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('rateable'); // rateable_type, rateable_id
            $table->unsignedTinyInteger('rating'); // 1-5 stars
            $table->text('review')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'rateable_type', 'rateable_id']);
        });

        // Add rating columns to job_postings if not exists
        if (! Schema::hasColumn('job_postings', 'rating')) {
            Schema::table('job_postings', function (Blueprint $table) {
                $table->decimal('rating', 3, 2)->nullable()->after('status');
                $table->unsignedInteger('rating_count')->default(0)->after('rating');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');

        if (Schema::hasColumn('job_postings', 'rating')) {
            Schema::table('job_postings', function (Blueprint $table) {
                $table->dropColumn(['rating', 'rating_count']);
            });
        }
    }
};
