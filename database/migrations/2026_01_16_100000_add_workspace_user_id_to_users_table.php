<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'workspace_user_id')) {
                $table->unsignedBigInteger('workspace_user_id')->nullable()->after('social_youtube');
            }
            if (! Schema::hasColumn('users', 'workspace_linked_at')) {
                $table->timestamp('workspace_linked_at')->nullable()->after('workspace_user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['workspace_user_id', 'workspace_linked_at']);
        });
    }
};
