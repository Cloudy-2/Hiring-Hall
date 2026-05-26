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

        Schema::table('chat_conversation', function (Blueprint $table) {
            if (! Schema::hasColumn('chat_conversation', 'invite_code')) {
                $table->string('invite_code', 32)->nullable()->after('type');
                $table->index('invite_code');
            }
            if (! Schema::hasColumn('chat_conversation', 'invite_enabled')) {
                $table->boolean('invite_enabled')->default(false)->after('invite_code');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('chat_conversation')) {
            return;
        }

        Schema::table('chat_conversation', function (Blueprint $table) {
            if (Schema::hasColumn('chat_conversation', 'invite_code')) {
                $table->dropIndex(['invite_code']);
                $table->dropColumn('invite_code');
            }
            if (Schema::hasColumn('chat_conversation', 'invite_enabled')) {
                $table->dropColumn('invite_enabled');
            }
        });
    }
};
