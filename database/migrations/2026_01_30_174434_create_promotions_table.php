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
        Schema::create('promotions', function (Blueprint $table) {
         $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('content_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('package_id')->constrained('promotion_packages');
    $table->enum('status', ['pending','approved','rejected'])->default('pending');
    $table->dateTime('starts_at')->nullable();
    $table->dateTime('ends_at')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
