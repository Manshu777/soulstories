<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE story_comments MODIFY body TEXT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE story_comments MODIFY body TEXT NOT NULL");
    }
};
