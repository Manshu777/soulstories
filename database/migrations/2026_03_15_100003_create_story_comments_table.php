<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('story_chapter_id')->constrained('story_chapters')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('story_comments')->cascadeOnDelete();
            $table->text('body');
            $table->unsignedInteger('line_number')->nullable(); // for line comments
            $table->enum('status', ['visible', 'hidden', 'removed'])->default('visible');
            $table->timestamps();

            $table->index(['story_chapter_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_comments');
    }
};
