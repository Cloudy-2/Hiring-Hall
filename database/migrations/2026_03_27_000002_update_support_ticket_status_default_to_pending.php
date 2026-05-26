<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        DB::table('support_tickets')->where('status', 'open')->update(['status' => 'pending']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE support_tickets MODIFY status VARCHAR(255) NOT NULL DEFAULT 'pending'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE support_tickets ALTER COLUMN status SET DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        DB::table('support_tickets')->where('status', 'pending')->update(['status' => 'open']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE support_tickets MODIFY status VARCHAR(255) NOT NULL DEFAULT 'open'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE support_tickets ALTER COLUMN status SET DEFAULT 'open'");
        }
    }
};
