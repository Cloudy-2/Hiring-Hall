<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('saved_applicants')) {
            return;
        }

        if (Schema::hasTable('saved_candidates')) {
            Schema::rename('saved_candidates', 'saved_applicants');

            return;
        }

        Schema::create('saved_applicants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('applicant_profile_id')->constrained('applicant_profiles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['employer_id', 'applicant_profile_id'], 'saved_applicants_employer_profile_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_applicants');
    }
};
