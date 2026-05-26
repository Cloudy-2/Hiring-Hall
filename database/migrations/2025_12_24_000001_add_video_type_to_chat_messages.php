<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip if chat_messages table doesn't exist
        if (! Schema::hasTable('chat_messages')) {
            return;
        }

        // Use database-specific syntax
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE chat_messages MODIFY COLUMN type ENUM('text','image','file','system','call','gif','video') NOT NULL DEFAULT 'text'");
        }
        // For SQLite, no action needed - type column already exists as string
    }

    public function down(): void
    {
        if (! Schema::hasTable('chat_messages')) {
            return;
        }

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE chat_messages MODIFY COLUMN type ENUM('text','image','file','system','call','gif') NOT NULL DEFAULT 'text'");
        }
    }
};
