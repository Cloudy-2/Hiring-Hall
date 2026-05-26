<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('new_updates')) {
            Schema::create('new_updates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('selector')->nullable();
                $table->string('url')->nullable();
                $table->text('description')->nullable();
                $table->string('icon')->default('bi-star');
                $table->string('category')->default('feature');
                $table->string('priority')->nullable();
                $table->string('status')->default('open');
                $table->boolean('is_active')->default(true);
                $table->timestamp('expires_at')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('new_updates');
    }
};
