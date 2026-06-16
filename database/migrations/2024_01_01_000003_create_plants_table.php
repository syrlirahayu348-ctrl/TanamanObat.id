<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('local_name');
            $table->string('latin_name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('benefits');
            $table->text('usage');
            $table->text('side_effects')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['published', 'draft'])->default('published');
            $table->unsignedBigInteger('views')->default(0);
            $table->string('origin')->nullable();
            $table->string('harvest_season')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
