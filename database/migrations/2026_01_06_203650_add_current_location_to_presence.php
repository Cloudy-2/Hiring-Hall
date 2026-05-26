<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_presence_statuses', function (Blueprint $table) {
            $table->foreignId('current_conversation_id')->nullable()->after('device_context')->constrained('chat_conversation')->nullOnDelete();
            $table->string('current_topic_slug')->nullable()->after('current_conversation_id');
        });
    }

    public function down(): void
    {
        Schema::table('chat_presence_statuses', function (Blueprint $table) {
            $table->dropForeign(['current_conversation_id']);
            $table->dropColumn(['current_conversation_id', 'current_topic_slug']);
        });
    }
};
