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
        if (Schema::hasTable('t_file_manager')) {
            return;
        }

        Schema::create('t_file_manager', function (Blueprint $table) {
            $table->id();
            $table->string('link', 20)->nullable();
            $table->string('name');
            $table->string('path')->nullable();
            $table->string('size')->nullable();
            $table->string('format')->nullable();
            $table->string('mime_type')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('is_folder')->default(0);
            $table->integer('isDeleted')->default(0);
            $table->string('parent_id')->nullable();
            $table->integer('uploader_id')->nullable();
            $table->string('google_drive_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_file_manager');
    }
};
