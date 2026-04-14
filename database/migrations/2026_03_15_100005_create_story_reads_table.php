<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('story_id')->constrained('stories')->cascadeOnDelete();
            $table->foreignId('story_chapter_id')->nullable()->constrained('story_chapters')->cascadeOnDelete();
            $table->timestamp('read_at')->useCurrent();

            $table->index(['user_id', 'story_id']);
            $table->index(['story_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_reads');
    }
};
