<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip if chat_conversation table doesn't exist
        if (! Schema::hasTable('chat_conversation')) {
            return;
        }

        // Add settings JSON column to chat_conversation table
        Schema::table('chat_conversation', function (Blueprint $table) {
            if (! Schema::hasColumn('chat_conversation', 'settings')) {
                $table->json('settings')->nullable()->after('invite_enabled');
            }
            if (! Schema::hasColumn('chat_conversation', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('settings');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('chat_conversation')) {
            return;
        }

        Schema::table('chat_conversation', function (Blueprint $table) {
            if (Schema::hasColumn('chat_conversation', 'settings')) {
                $table->dropColumn('settings');
            }
            if (Schema::hasColumn('chat_conversation', 'is_public')) {
                $table->dropColumn('is_public');
            }
        });
    }
};
