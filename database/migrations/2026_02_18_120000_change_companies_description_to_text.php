<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('companies') && Schema::hasColumn('companies', 'description')) {
            DB::statement('ALTER TABLE companies MODIFY description TEXT NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('companies') && Schema::hasColumn('companies', 'description')) {
            DB::statement('ALTER TABLE companies MODIFY description VARCHAR(255) NULL');
        }
    }
};
