<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name_1', 100);
            $table->string('last_name_2', 100)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // Possible future functionality
            $table->string('password');
            $table->date('birthdate');
            $table->string('address', 200);
            $table->enum('document_type', User::DOCUMENT_TYPES)->default(User::DOCUMENT_DNI);
            $table->string('document_number', 20)->unique();
            $table->string('phone', 25);
            $table->enum('role', User::ROLES)->default(User::ROLE_USER);
            $table->text('comments')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // We won't use password reset functionality for now.
        /*
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        */

        // Sessions will be handled by Laravel itself for now.
        // For that reason "SESSION_DRIVER=file" in both .env and .env.example file instead of "database"
        /*
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        // We won't use password reset functionality for now.
        //Schema::dropIfExists('password_reset_tokens');
        // Sessions will be handled by Laravel itself for now.
        //Schema::dropIfExists('sessions');
    }
};
