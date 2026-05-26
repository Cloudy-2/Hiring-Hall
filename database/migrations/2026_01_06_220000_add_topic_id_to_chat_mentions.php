<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_mentions', function (Blueprint $table) {
            if (! Schema::hasColumn('chat_mentions', 'topic_id')) {
                $table->unsignedBigInteger('topic_id')->nullable()->after('conversation_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chat_mentions', function (Blueprint $table) {
            $table->dropColumn('topic_id');
        });
    }
};
