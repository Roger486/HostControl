<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(5)->create(['role' => User::ROLE_ADMIN]);
        User::factory()->count(50)->create(['role' => User::ROLE_USER]);
    }
}
