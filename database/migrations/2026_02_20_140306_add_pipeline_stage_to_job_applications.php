<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('job_applications', 'pipeline_stage_id')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->unsignedBigInteger('pipeline_stage_id')->nullable()->after('status');
            });
        }

        try {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->foreign('pipeline_stage_id')->references('id')->on('pipeline_stages')->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // Foreign key may already exist
        }
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            try {
                $table->dropForeign(['pipeline_stage_id']);
            } catch (\Throwable $e) {
                // Foreign key may not exist
            }
            $table->dropColumn('pipeline_stage_id');
        });
    }
};
