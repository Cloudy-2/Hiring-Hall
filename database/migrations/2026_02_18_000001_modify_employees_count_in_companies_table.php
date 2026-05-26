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
        if (Schema::hasTable('companies') && Schema::hasColumn('companies', 'employees_count')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('employees_count')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Reverting to integer might lose data if it contains non-integers,
            // but we define the reverse operation anyway.
            // Using DB::statement or just change() if possible, but change() to int from string "1-10" will fail or truncate.
            // For safety in down(), we might just leave it or try to change back.
            // $table->unsignedInteger('employees_count')->nullable()->change();
        });
    }
};
