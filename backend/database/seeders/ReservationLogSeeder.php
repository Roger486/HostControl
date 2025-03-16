<?php

namespace Database\Seeders;

use App\Models\ReservationLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReservationLog::factory()->count(100)->create();
    }
}
