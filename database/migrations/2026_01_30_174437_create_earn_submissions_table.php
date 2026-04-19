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
        Schema::create('earn_submissions', function (Blueprint $table) {
            $table->id();
    $table->foreignId('earn_order_id')->constrained()->cascadeOnDelete();
    $table->foreignId('task_id')->constrained('earn_tasks');
    $table->string('proof_link');
    $table->enum('status', ['pending','approved','rejected'])->default('pending');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('earn_submissions');
    }
};
