<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Business Registration
            $table->string('registration_type')->nullable()->after('employees_count');
            $table->string('registration_number')->nullable()->after('registration_type');
            $table->string('registration_document_url')->nullable()->after('registration_number');

            // Business Address
            $table->string('business_address')->nullable()->after('registration_document_url');
            $table->string('city')->nullable()->after('business_address');
            $table->string('province')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('province');
            $table->string('country')->default('Philippines')->after('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'registration_type',
                'registration_number',
                'registration_document_url',
                'business_address',
                'city',
                'province',
                'postal_code',
                'country',
            ]);
        });
    }
};
