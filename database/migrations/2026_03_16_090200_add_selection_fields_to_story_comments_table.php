<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('story_comments', function (Blueprint $table) {
            if (! Schema::hasColumn('story_comments', 'start_index')) {
                $table->unsignedInteger('start_index')->nullable()->after('line_number');
            }
            if (! Schema::hasColumn('story_comments', 'end_index')) {
                $table->unsignedInteger('end_index')->nullable()->after('start_index');
            }
            if (! Schema::hasColumn('story_comments', 'selected_text')) {
                $table->text('selected_text')->nullable()->after('end_index');
            }
            if (! Schema::hasColumn('story_comments', 'reaction')) {
                $table->enum('reaction', ['heart', 'fire', 'sad'])->nullable()->after('selected_text');
            }
        });
    }

    public function down(): void
    {
        Schema::table('story_comments', function (Blueprint $table) {
            $drops = [];
            foreach (['start_index', 'end_index', 'selected_text', 'reaction'] as $col) {
                if (Schema::hasColumn('story_comments', $col)) {
                    $drops[] = $col;
                }
            }
            if ($drops) {
                $table->dropColumn($drops);
            }
        });
    }
};
