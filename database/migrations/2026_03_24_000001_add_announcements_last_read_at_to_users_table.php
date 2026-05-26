<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'announcements_last_read_at')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('announcements_last_read_at')->nullable()->after('terms_shared_data_at');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'announcements_last_read_at')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('announcements_last_read_at');
        });
    }
};
