<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('moderation_status')->default('approved')->after('status');
            $table->foreignId('moderated_by')->nullable()->after('moderation_status')->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            $table->text('moderation_notes')->nullable()->after('moderated_at');
            $table->boolean('is_flagged')->default(false)->after('moderation_notes');
            $table->text('flag_reason')->nullable()->after('is_flagged');

            $table->index('moderation_status');
            $table->index('is_flagged');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropIndex(['moderation_status']);
            $table->dropIndex(['is_flagged']);
            $table->dropColumn([
                'moderation_status',
                'moderated_by',
                'moderated_at',
                'moderation_notes',
                'is_flagged',
                'flag_reason',
            ]);
        });
    }
};
