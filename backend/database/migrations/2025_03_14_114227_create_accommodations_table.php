<?php

use App\Models\Accommodation\Accommodation;
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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('accommodation_code', 6)->unique();
            $table->string('section', 50);
            $table->integer('capacity');
            $table->integer('price_per_day'); // in centims of € to avoid float errors
            $table->boolean('is_available')->default(false);
            $table->text('comments')->nullable();
            $table->enum('type', Accommodation::TYPES);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
