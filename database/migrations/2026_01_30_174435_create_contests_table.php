<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contests', function (Blueprint $table) {
             $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['series','story','poem','quote']);
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('cover_image')->nullable();
    $table->enum('status', ['draft','published'])->default('draft');
    $table->boolean('is_paid')->default(false);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contests');
    }
};
