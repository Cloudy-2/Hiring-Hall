<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip if chat_messages table doesn't exist
        if (! Schema::hasTable('chat_messages')) {
            return;
        }

        Schema::table('chat_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('chat_messages', 'deleted_by_moderator')) {
                $table->boolean('deleted_by_moderator')->default(false)->after('body');
            }
            if (! Schema::hasColumn('chat_messages', 'original_body')) {
                $table->text('original_body')->nullable()->after('deleted_by_moderator');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('chat_messages')) {
            return;
        }

        Schema::table('chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('chat_messages', 'deleted_by_moderator')) {
                $table->dropColumn('deleted_by_moderator');
            }
            if (Schema::hasColumn('chat_messages', 'original_body')) {
                $table->dropColumn('original_body');
            }
        });
    }
};
