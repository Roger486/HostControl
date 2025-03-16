<?php

namespace Database\Seeders;

use App\Models\Accommodation\Accommodation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Accommodation::factory()->count(10)->create(['type' => 'CampingSpot']);
        Accommodation::factory()->count(10)->create(['type' => 'Bungalow']);
        Accommodation::factory()->count(10)->create(['type' => 'Room']);
        Accommodation::factory()->count(10)->create(['type' => 'House']);
    }
}
