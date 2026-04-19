<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('story_chapters', function (Blueprint $table) {
            if (! Schema::hasColumn('story_chapters', 'word_count')) {
                $table->unsignedInteger('word_count')->default(0)->after('content');
            }
            if (! Schema::hasColumn('story_chapters', 'reading_time')) {
                $table->unsignedInteger('reading_time')->default(0)->after('word_count');
            }
            if (! Schema::hasColumn('story_chapters', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('audio_file');
            }
            if (! Schema::hasColumn('story_chapters', 'continue_reading_after')) {
                $table->unsignedInteger('continue_reading_after')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('story_chapters', function (Blueprint $table) {
            $drops = [];
            foreach (['word_count', 'reading_time', 'youtube_url', 'continue_reading_after'] as $col) {
                if (Schema::hasColumn('story_chapters', $col)) {
                    $drops[] = $col;
                }
            }
            if ($drops) {
                $table->dropColumn($drops);
            }
        });
    }
};
