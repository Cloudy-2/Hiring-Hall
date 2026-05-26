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
            $table->string('contact_name')->nullable()->after('phone');
            $table->string('contact_position')->nullable()->after('contact_name');
            $table->string('contact_availability_time')->nullable()->after('contact_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['contact_name', 'contact_position', 'contact_availability_time']);
        });
    }
};
