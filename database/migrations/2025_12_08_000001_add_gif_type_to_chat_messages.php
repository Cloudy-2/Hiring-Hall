<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            DB::statement("ALTER TABLE chat_messages MODIFY COLUMN type ENUM('text','image','file','system','call','gif') NOT NULL DEFAULT 'text'");
        } else {
            // For SQLite and other databases, use Laravel's schema builder
            // SQLite doesn't support ENUM, so we use string
            if (! Schema::hasColumn('chat_messages', 'type')) {
                Schema::table('chat_messages', function (Blueprint $table) {
                    $table->string('type')->default('text');
                });
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('chat_messages')) {
            return;
        }

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE chat_messages MODIFY COLUMN type ENUM('text','image','file','system','call') NOT NULL DEFAULT 'text'");
        }
        // For SQLite, no action needed as we can't modify column types easily
    }
};
