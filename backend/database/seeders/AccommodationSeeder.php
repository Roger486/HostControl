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
        foreach (Accommodation::TYPES as $accomodationType) {
            Accommodation::factory()->count(10)->create(['type' => $accomodationType]);
        }
    }
}
