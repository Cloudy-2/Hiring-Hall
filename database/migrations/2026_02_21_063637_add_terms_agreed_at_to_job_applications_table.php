<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('job_applications', 'terms_agreed_at')) {
            return;
        }
        Schema::table('job_applications', function (Blueprint $table) {
            $table->timestamp('terms_agreed_at')->nullable()->after('applied_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('job_applications', 'terms_agreed_at')) {
            return;
        }
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('terms_agreed_at');
        });
    }
};
