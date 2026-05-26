<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Rename the table
        if (Schema::hasTable('candidate_profiles') && ! Schema::hasTable('applicant_profiles')) {
            Schema::rename('candidate_profiles', 'applicant_profiles');
        }

        // 2. Rename foreign key columns in related tables
        if (Schema::hasTable('job_applications') && Schema::hasColumn('job_applications', 'candidate_profile_id')) {
            Schema::table('job_applications', function (Blueprint $table) {
                // Drop old FK first, then rename
                try {
                    $table->dropForeign(['candidate_profile_id']);
                } catch (\Exception $e) {
                    // FK might not exist
                }
                $table->renameColumn('candidate_profile_id', 'applicant_profile_id');
            });
            // Re-add FK after rename
            Schema::table('job_applications', function (Blueprint $table) {
                $table->foreign('applicant_profile_id')->references('id')->on('applicant_profiles')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('saved_jobs') && Schema::hasColumn('saved_jobs', 'candidate_profile_id')) {
            Schema::table('saved_jobs', function (Blueprint $table) {
                try {
                    $table->dropForeign(['candidate_profile_id']);
                } catch (\Exception $e) {
                    // FK might not exist
                }
                $table->renameColumn('candidate_profile_id', 'applicant_profile_id');
            });
            Schema::table('saved_jobs', function (Blueprint $table) {
                $table->foreign('applicant_profile_id')->references('id')->on('applicant_profiles')->nullOnDelete();
            });
        }

        if (Schema::hasTable('saved_candidates') && Schema::hasColumn('saved_candidates', 'candidate_profile_id')) {
            $foreignKeys = Schema::getForeignKeys('saved_candidates');
            $fkNames = array_column($foreignKeys, 'name');

            $indexes = Schema::getIndexes('saved_candidates');
            $idxNames = array_column($indexes, 'name');

            Schema::table('saved_candidates', function (Blueprint $table) use ($fkNames, $idxNames) {
                // Drop all constraints that might depend on the unique index
                if (in_array('saved_candidates_candidate_profile_id_foreign', $fkNames)) {
                    $table->dropForeign(['candidate_profile_id']);
                }
                if (in_array('saved_candidates_employer_id_foreign', $fkNames)) {
                    $table->dropForeign(['employer_id']);
                }
                if (in_array('saved_candidates_employer_id_candidate_profile_id_unique', $idxNames)) {
                    $table->dropUnique(['employer_id', 'candidate_profile_id']);
                }

                // Cleanup leftover indexes if they exist as separate indexes
                if (in_array('saved_candidates_candidate_profile_id_foreign', $idxNames)) {
                    $table->dropIndex('saved_candidates_candidate_profile_id_foreign');
                }
                if (in_array('saved_candidates_employer_id_foreign', $idxNames)) {
                    $table->dropIndex('saved_candidates_employer_id_foreign');
                }

                $table->renameColumn('candidate_profile_id', 'applicant_profile_id');
            });

            Schema::table('saved_candidates', function (Blueprint $table) {
                $table->foreign('applicant_profile_id')->references('id')->on('applicant_profiles')->cascadeOnDelete();
                $table->foreign('employer_id')->references('id')->on('users')->cascadeOnDelete();
                $table->unique(['employer_id', 'applicant_profile_id']);
            });
        }
    }

    public function down(): void
    {
        // Reverse: rename back
        if (Schema::hasTable('saved_candidates') && Schema::hasColumn('saved_candidates', 'applicant_profile_id')) {
            $foreignKeys = Schema::getForeignKeys('saved_candidates');
            $fkNames = array_column($foreignKeys, 'name');

            $indexes = Schema::getIndexes('saved_candidates');
            $idxNames = array_column($indexes, 'name');

            Schema::table('saved_candidates', function (Blueprint $table) use ($fkNames, $idxNames) {
                if (in_array('saved_candidates_applicant_profile_id_foreign', $fkNames)) {
                    $table->dropForeign(['applicant_profile_id']);
                }
                if (in_array('saved_candidates_employer_id_foreign', $fkNames)) {
                    $table->dropForeign(['employer_id']);
                }
                if (in_array('saved_candidates_employer_id_applicant_profile_id_unique', $idxNames)) {
                    $table->dropUnique(['employer_id', 'applicant_profile_id']);
                }

                // Cleanup leftover indexes if they exist as separate indexes
                if (in_array('saved_candidates_applicant_profile_id_foreign', $idxNames)) {
                    $table->dropIndex('saved_candidates_applicant_profile_id_foreign');
                }
                if (in_array('saved_candidates_employer_id_foreign', $idxNames)) {
                    $table->dropIndex('saved_candidates_employer_id_foreign');
                }

                $table->renameColumn('applicant_profile_id', 'candidate_profile_id');
            });

            Schema::table('saved_candidates', function (Blueprint $table) {
                $table->foreign('candidate_profile_id')->references('id')->on('candidate_profiles')->cascadeOnDelete();
                $table->foreign('employer_id')->references('id')->on('users')->cascadeOnDelete();
                $table->unique(['employer_id', 'candidate_profile_id']);
            });
        }

        if (Schema::hasTable('saved_jobs') && Schema::hasColumn('saved_jobs', 'applicant_profile_id')) {
            Schema::table('saved_jobs', function (Blueprint $table) {
                try {
                    $table->dropForeign(['applicant_profile_id']);
                } catch (\Exception $e) {
                }
                $table->renameColumn('applicant_profile_id', 'candidate_profile_id');
            });
            Schema::table('saved_jobs', function (Blueprint $table) {
                $table->foreign('candidate_profile_id')->references('id')->on('candidate_profiles')->nullOnDelete();
            });
        }

        if (Schema::hasTable('job_applications') && Schema::hasColumn('job_applications', 'applicant_profile_id')) {
            Schema::table('job_applications', function (Blueprint $table) {
                try {
                    $table->dropForeign(['applicant_profile_id']);
                } catch (\Exception $e) {
                }
                $table->renameColumn('applicant_profile_id', 'candidate_profile_id');
            });
            Schema::table('job_applications', function (Blueprint $table) {
                $table->foreign('candidate_profile_id')->references('id')->on('candidate_profiles')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('applicant_profiles') && ! Schema::hasTable('candidate_profiles')) {
            Schema::rename('applicant_profiles', 'candidate_profiles');
        }
    }
};
