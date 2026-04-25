<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->index('title');
            $table->index('genre');
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText(['title', 'description']);
            }
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->index('title');
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText(['title', 'body']);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('name');
            $table->index('username');
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText(['name', 'username']);
            }
        });

        Schema::create('search_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->unsignedBigInteger('count')->default(0);
            $table->timestamp('last_searched_at')->nullable();
            $table->timestamps();
        });

        Schema::create('search_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('query');
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_histories');
        Schema::dropIfExists('search_keywords');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['username']);
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropFullText(['name', 'username']);
            }
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex(['title']);
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropFullText(['title', 'body']);
            }
        });

        Schema::table('stories', function (Blueprint $table) {
            $table->dropIndex(['title']);
            $table->dropIndex(['genre']);
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropFullText(['title', 'description']);
            }
        });
    }
};
