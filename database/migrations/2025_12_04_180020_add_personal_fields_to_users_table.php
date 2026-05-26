<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('profile_photo_path');
            $table->string('gender', 20)->nullable()->after('date_of_birth');
            $table->string('phone', 50)->nullable()->after('gender');
            $table->string('marital_status', 50)->nullable()->after('phone');
            $table->text('address')->nullable()->after('marital_status');
            $table->string('social_facebook')->nullable()->after('address');
            $table->string('social_twitter')->nullable()->after('social_facebook');
            $table->string('social_instagram')->nullable()->after('social_twitter');
            $table->string('social_github')->nullable()->after('social_instagram');
            $table->string('social_youtube')->nullable()->after('social_github');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'phone',
                'marital_status',
                'address',
                'social_facebook',
                'social_twitter',
                'social_instagram',
                'social_github',
                'social_youtube',
            ]);
        });
    }
};
