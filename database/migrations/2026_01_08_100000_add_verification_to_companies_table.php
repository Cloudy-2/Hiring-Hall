<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (! Schema::hasColumn('companies', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('companies', 'verification_status')) {
                $table->string('verification_status')->default('pending')->after('verified'); // pending, approved, rejected
                $table->text('rejection_reason')->nullable()->after('verification_status');
                $table->timestamp('verified_at')->nullable()->after('rejection_reason');
                $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('companies', 'description')) {
                $table->string('description')->nullable()->after('industry');
                $table->string('website')->nullable()->after('description');
                $table->string('email')->nullable()->after('website');
                $table->string('phone')->nullable()->after('email');
            }
        });

        // Update existing companies to be approved
        // \DB::table('companies')->update(['verification_status' => 'approved', 'verified' => true]);
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'user_id',
                'verification_status',
                'rejection_reason',
                'verified_at',
                'verified_by',
                'description',
                'website',
                'email',
                'phone',
            ]);
        });
    }
};
