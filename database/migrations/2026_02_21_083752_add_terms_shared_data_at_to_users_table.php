<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Store data-sharing terms acceptance on the user; remove from companies and applicant_profiles.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'terms_shared_data_at')) {
            return;
        }
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('terms_shared_data_at')->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('users', 'terms_shared_data_at')) {
            return;
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('terms_shared_data_at');
        });
    }
};
