<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            // Availability is already present as a string; we will reuse it for
            // values like "Available Now" and "30 Days Notice".
            $table->string('job_type', 50)->nullable()->after('availability');
            $table->longText('expertise_categories')->nullable()->after('job_type');
        });
    }

    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn(['job_type', 'expertise_categories']);
        });
    }
};
