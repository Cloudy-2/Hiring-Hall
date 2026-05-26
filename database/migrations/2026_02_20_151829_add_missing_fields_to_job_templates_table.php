<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_templates', function (Blueprint $table) {
            if (! Schema::hasColumn('job_templates', 'industry_type')) {
                $table->string('industry_type')->nullable()->after('category');
            }
            if (! Schema::hasColumn('job_templates', 'recruiter_type')) {
                $table->string('recruiter_type')->nullable()->after('industry_type');
            }
            if (! Schema::hasColumn('job_templates', 'vacancies')) {
                $table->integer('vacancies')->nullable()->after('recruiter_type');
            }
            if (! Schema::hasColumn('job_templates', 'experience_min_years')) {
                $table->integer('experience_min_years')->nullable()->after('vacancies');
            }
            if (! Schema::hasColumn('job_templates', 'experience_max_years')) {
                $table->integer('experience_max_years')->nullable()->after('experience_min_years');
            }
            if (! Schema::hasColumn('job_templates', 'responsibilities')) {
                $table->text('responsibilities')->nullable()->after('description');
            }
            if (! Schema::hasColumn('job_templates', 'highlight_work_setup')) {
                $table->string('highlight_work_setup')->nullable()->after('benefits');
            }
            if (! Schema::hasColumn('job_templates', 'highlight_shift_schedule')) {
                $table->string('highlight_shift_schedule')->nullable()->after('highlight_work_setup');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_templates', function (Blueprint $table) {
            $table->dropColumn([
                'industry_type',
                'recruiter_type',
                'vacancies',
                'experience_min_years',
                'experience_max_years',
                'responsibilities',
                'highlight_work_setup',
                'highlight_shift_schedule',
            ]);
        });
    }
};
