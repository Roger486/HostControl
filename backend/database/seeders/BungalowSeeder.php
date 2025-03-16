<?php

namespace Database\Seeders;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\Bungalow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BungalowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gets all the Accommodations of CampingSpot type without a linked Bungalow
        $accommodationsWithoutCampingSpot = Accommodation::where('type', 'Bungalow')
            ->whereDoesntHave('Bungalow')->get();
        // Gives every row a linked Bungalow freshly created
        $accommodationsWithoutCampingSpot->each(function ($accommodation) {
            Bungalow::factory()->create(['accommodation_id' => $accommodation->id,]);
        });
    }
}
