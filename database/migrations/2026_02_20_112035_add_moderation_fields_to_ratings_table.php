<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('review');
            $table->boolean('is_flagged')->default(false)->after('is_hidden');
            $table->text('flag_reason')->nullable()->after('is_flagged');
            $table->foreignId('moderated_by')->nullable()->after('flag_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            $table->text('moderation_notes')->nullable()->after('moderated_at');

            $table->index('is_hidden');
            $table->index('is_flagged');
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropIndex(['is_hidden']);
            $table->dropIndex(['is_flagged']);
            $table->dropColumn([
                'is_hidden',
                'is_flagged',
                'flag_reason',
                'moderated_by',
                'moderated_at',
                'moderation_notes',
            ]);
        });
    }
};
