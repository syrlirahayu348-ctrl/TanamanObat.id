<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('comments', 'parent_id')) {
            try {
                Schema::table('comments', function (Blueprint $table) {
                    $table->dropForeign(['parent_id']);
                });
            } catch (\Exception $e) {}

            try {
                Schema::table('comments', function (Blueprint $table) {
                    $table->dropColumn('parent_id');
                });
            } catch (\Exception $e) {}
        }
        
        try {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropForeign(['plant_id']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropUnique('comments_user_id_plant_id_unique');
            });
        } catch (\Exception $e) {}

        // Now reconstruct everything correctly
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('cascade');
            $table->unsignedTinyInteger('rating')->nullable()->change();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('comments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropForeign(['parent_id']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('parent_id');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropForeign(['plant_id']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('comments', function (Blueprint $table) {
                $table->unique(['user_id', 'plant_id']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('comments', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('plant_id')->references('id')->on('plants')->onDelete('cascade');
                $table->unsignedTinyInteger('rating')->nullable(false)->change();
            });
        } catch (\Exception $e) {}
    }
};
