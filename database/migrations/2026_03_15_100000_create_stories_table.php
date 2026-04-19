<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->string('genre')->nullable();
            $table->json('tags')->nullable(); // ['tag1', 'tag2']
            $table->enum('language', ['hindi', 'hinglish'])->default('hinglish');
            $table->enum('story_type', ['short_story', 'series'])->default('short_story');
            $table->string('content_guidance')->nullable();
            $table->enum('visibility', ['public', 'draft'])->default('draft');
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->unsignedInteger('read_time')->default(0); // total minutes, auto-calculated
            $table->timestamps();

            $table->index(['user_id', 'approval_status', 'visibility']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
