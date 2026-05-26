<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('highlight_work_setup')->nullable()->after('responsibilities');
            $table->string('highlight_shift_schedule')->nullable()->after('highlight_work_setup');
            $table->string('highlight_monthly_rate')->nullable()->after('highlight_shift_schedule');
            $table->string('highlight_benefits')->nullable()->after('highlight_monthly_rate');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn([
                'highlight_work_setup',
                'highlight_shift_schedule',
                'highlight_monthly_rate',
                'highlight_benefits',
            ]);
        });
    }
};
