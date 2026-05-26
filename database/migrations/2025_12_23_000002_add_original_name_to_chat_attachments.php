<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_attachments', function (Blueprint $table) {
            if (! Schema::hasColumn('chat_attachments', 'original_name')) {
                $table->string('original_name')->nullable()->after('path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chat_attachments', function (Blueprint $table) {
            $table->dropColumn('original_name');
        });
    }
};
