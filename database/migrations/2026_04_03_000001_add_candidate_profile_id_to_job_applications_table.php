<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('job_applications', 'candidate_profile_id')) {
            Schema::table('job_applications', function (Blueprint $table) {
                // Keep this migration idempotent across drifted DBs.
                // Some environments already have the FK/index name, which causes duplicate key errors.
                $table->unsignedBigInteger('candidate_profile_id')
                    ->nullable()
                    ->after('job_posting_id');
            });
        }

        if (Schema::hasColumn('job_applications', 'candidate_profile_id')) {
            DB::table('job_applications')
                ->whereNull('candidate_profile_id')
                ->orderBy('id')
                ->chunkById(100, function ($applications): void {
                    foreach ($applications as $application) {
                        $profileId = DB::table('applicant_profiles')
                            ->where('user_id', $application->user_id)
                            ->value('id');

                        if ($profileId) {
                            DB::table('job_applications')
                                ->where('id', $application->id)
                                ->update(['candidate_profile_id' => $profileId]);
                        }
                    }
                });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('job_applications', 'candidate_profile_id')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->dropColumn('candidate_profile_id');
            });
        }
    }
};
