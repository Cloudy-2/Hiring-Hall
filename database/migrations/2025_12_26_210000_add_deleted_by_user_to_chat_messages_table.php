<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('chat_messages', 'deleted_by_user')) {
                $table->boolean('deleted_by_user')->default(false)->after('deleted_by_moderator');
            }
            if (! Schema::hasColumn('chat_messages', 'original_body')) {
                $table->text('original_body')->nullable()->after('body');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['deleted_by_user', 'original_body']);
        });
    }
};
