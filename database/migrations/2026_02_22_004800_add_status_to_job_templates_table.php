<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_templates', function (Blueprint $table) {
            if (! Schema::hasColumn('job_templates', 'status')) {
                $table->string('status', 20)->default('draft')->after('is_default');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_templates', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
