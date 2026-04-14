<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE stories MODIFY story_type ENUM('short_story','series','poems') NOT NULL DEFAULT 'short_story'");
    }

    public function down(): void
    {
        // rollback-safe: map poems back to short_story before shrinking enum
        DB::statement("UPDATE stories SET story_type = 'short_story' WHERE story_type = 'poems'");
        DB::statement("ALTER TABLE stories MODIFY story_type ENUM('short_story','series') NOT NULL DEFAULT 'short_story'");
    }
};
