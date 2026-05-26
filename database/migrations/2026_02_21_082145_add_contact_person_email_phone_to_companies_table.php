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
        if (Schema::hasColumn('companies', 'contact_person_email') && Schema::hasColumn('companies', 'contact_person_phone')) {
            return;
        }
        Schema::table('companies', function (Blueprint $table) {
            if (! Schema::hasColumn('companies', 'contact_person_email')) {
                $table->string('contact_person_email')->nullable()->after('contact_availability_time');
            }
            if (! Schema::hasColumn('companies', 'contact_person_phone')) {
                $table->string('contact_person_phone')->nullable()->after('contact_person_email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('companies', 'contact_person_email') && ! Schema::hasColumn('companies', 'contact_person_phone')) {
            return;
        }
        Schema::table('companies', function (Blueprint $table) {
            $columns = array_filter(
                ['contact_person_email', 'contact_person_phone'],
                fn (string $col) => Schema::hasColumn('companies', $col)
            );
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
