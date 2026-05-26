<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->longText('career_objective')->nullable()->after('cv_path');
            $table->longText('education_details')->nullable()->after('career_objective');
            $table->longText('certifications')->nullable()->after('education_details');
            $table->longText('key_achievements')->nullable()->after('certifications');
            $table->longText('activities_interests')->nullable()->after('key_achievements');
            $table->longText('references_block')->nullable()->after('activities_interests');
            $table->longText('experience_overview')->nullable()->after('references_block');
        });
    }

    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'career_objective',
                'education_details',
                'certifications',
                'key_achievements',
                'activities_interests',
                'references_block',
                'experience_overview',
            ]);
        });
    }
};
