<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->string('document_number', 20)->nullable();
            $table->enum('document_type', ['DNI', 'NIE', 'Passport'])->default('DNI');
            $table->string('first_name', 100);
            $table->string('last_name_1', 100);
            $table->string('last_name_2', 100)->nullable();
            $table->date('birthdate');
            $table->text('comments')->nullable();
            $table->timestamps();
            //Unique composite key unless no document is provided
            $table->unique(['reservation_id', 'document_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companions');
    }
};
