<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function tableName(): string
    {
        return Schema::hasTable('applicant_profiles') ? 'applicant_profiles' : 'candidate_profiles';
    }

    public function up(): void
    {
        $tableName = $this->tableName();
        Schema::table($tableName, function (Blueprint $table) {
            $table->string('verification_status')->default('pending')->after('verified');
            $table->foreignId('verified_by')->nullable()->after('verification_status')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('verification_notes')->nullable()->after('verified_at');

            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        $tableName = $this->tableName();
        Schema::table($tableName, function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropIndex(['verification_status']);
            $table->dropColumn([
                'verification_status',
                'verified_by',
                'verified_at',
                'verification_notes',
            ]);
        });
    }
};
