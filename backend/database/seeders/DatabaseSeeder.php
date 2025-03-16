<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(AccommodationSeeder::class);
        // Subtypes of Accomodation
        $this->call(CampingSpotSeeder::class);
        $this->call(BungalowSeeder::class);
        $this->call(HouseSeeder::class);
        $this->call(RoomSeeder::class);
    }
}
