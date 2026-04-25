<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::table('stories', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('genre')->constrained('categories')->nullOnDelete();
            $table->index('category_id');
        });

        if (Schema::hasColumn('stories', 'genre')) {
            $genres = DB::table('stories')
                ->whereNotNull('genre')
                ->where('genre', '<>', '')
                ->distinct()
                ->pluck('genre');

            foreach ($genres as $genre) {
                $slug = Str::slug((string) $genre);
                if (! $slug) {
                    continue;
                }

                DB::table('categories')->updateOrInsert(
                    ['slug' => $slug],
                    [
                        'name' => (string) $genre,
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $categoryId = DB::table('categories')->where('slug', $slug)->value('id');
                DB::table('stories')->where('genre', $genre)->update(['category_id' => $categoryId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
