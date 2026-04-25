<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('follows')) {
            Schema::create('follows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('following_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['follower_id', 'following_id']);
                $table->index('follower_id');
                $table->index('following_id');
            });
        }

        if (Schema::hasTable('followers')) {
            $rows = DB::table('followers')->select('follower_id', 'following_id', 'created_at', 'updated_at')->get();
            foreach ($rows as $row) {
                DB::table('follows')->updateOrInsert(
                    [
                        'follower_id' => $row->follower_id,
                        'following_id' => $row->following_id,
                    ],
                    [
                        'created_at' => $row->created_at ?? now(),
                        'updated_at' => $row->updated_at ?? now(),
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
