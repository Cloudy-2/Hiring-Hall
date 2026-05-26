<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables to add soft deletes to.
     *
     * @var array<string>
     */
    protected array $tables = [
        'users',
        'companies',
        'applicant_profiles',
        'job_templates',
        'pipeline_stages',
        'interviews',
        'announcements',
        'faqs',
        'support_tickets',
        'support_ticket_replies',
        'support_ticket_attachments',
        'bulk_notifications',
        'activity_logs',
        'email_templates',
        'dropdown_options',
        'ratings',
        'job_alert_preferences',
        'personal_tags',
        'chat_todos',
        'impersonation_logs',
        'release_notes',
        'new_updates',
        'settings',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
