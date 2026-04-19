<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('story_id')->nullable()->constrained('stories')->cascadeOnDelete();
            $table->foreignId('story_chapter_id')->nullable()->constrained('story_chapters')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'story_id'], 'story_likes_user_story_unique');
            $table->unique(['user_id', 'story_chapter_id'], 'story_likes_user_chapter_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_likes');
    }
};
