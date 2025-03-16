<?php

namespace Database\Seeders;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\CampingSpot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampingSpotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gets all the Accommodations of CampingSpot type without a linked CampingSpot
        $accommodationsWithoutCampingSpot = Accommodation::where('type', 'CampingSpot')
            ->whereDoesntHave('CampingSpot')->get();
        // Gives every row a linked CampingSpot freshly created
        $accommodationsWithoutCampingSpot->each(function ($accommodation) {
            CampingSpot::factory()->create(['accommodation_id' => $accommodation->id,]);
        });
    }
}
