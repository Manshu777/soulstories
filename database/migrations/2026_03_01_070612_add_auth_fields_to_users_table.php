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
        Schema::table('users', function (Blueprint $table) {
          
        $table->string('phone')->nullable()->after('email');
        $table->date('dob')->nullable()->after('phone');
        $table->string('pronouns')->nullable()->after('dob');
        $table->string('google_id')->nullable()->after('pronouns');
        $table->boolean('is_verified')->default(false)->after('google_id');
        $table->string('otp')->nullable()->after('is_verified');
        $table->timestamp('otp_expires_at')->nullable()->after('otp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
