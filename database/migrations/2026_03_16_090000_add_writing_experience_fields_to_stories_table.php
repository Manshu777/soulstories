<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            if (! Schema::hasColumn('stories', 'type')) {
                $table->enum('type', ['short_story', 'series', 'poems'])->default('short_story')->after('story_type');
            }
            if (! Schema::hasColumn('stories', 'theme')) {
                $table->enum('theme', ['light', 'dark', 'sepia'])->default('light')->after('visibility');
            }
            if (! Schema::hasColumn('stories', 'bg_image')) {
                $table->string('bg_image')->nullable()->after('theme');
            }
            if (! Schema::hasColumn('stories', 'bg_color')) {
                $table->string('bg_color', 20)->nullable()->after('bg_image');
            }
        });

        DB::statement("ALTER TABLE stories MODIFY visibility ENUM('draft','public','premium') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE stories MODIFY visibility ENUM('draft','public') NOT NULL DEFAULT 'draft'");

        Schema::table('stories', function (Blueprint $table) {
            $drops = [];
            foreach (['type', 'theme', 'bg_image', 'bg_color'] as $col) {
                if (Schema::hasColumn('stories', $col)) {
                    $drops[] = $col;
                }
            }
            if ($drops) {
                $table->dropColumn($drops);
            }
        });
    }
};
