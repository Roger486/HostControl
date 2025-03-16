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
        $this->call([
            UserSeeder::class,
            AccommodationSeeder::class,
            // Subtypes of Accomodation
            CampingSpotSeeder::class,
            BungalowSeeder::class,
            RoomSeeder::class,
            HouseSeeder::class,
            // With users and accommodations we can create reservations
            ReservationSeeder::class,
            // With reservations we can create companions
            CompanionSeeder::class
        ]);
    }
}
