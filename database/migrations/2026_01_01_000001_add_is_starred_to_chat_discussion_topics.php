<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chat_discussion_topics')) {
            Schema::table('chat_discussion_topics', function (Blueprint $table) {
                if (! Schema::hasColumn('chat_discussion_topics', 'is_starred')) {
                    $table->boolean('is_starred')->default(false)->after('is_readonly');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('chat_discussion_topics')) {
            Schema::table('chat_discussion_topics', function (Blueprint $table) {
                if (Schema::hasColumn('chat_discussion_topics', 'is_starred')) {
                    $table->dropColumn('is_starred');
                }
            });
        }
    }
};
