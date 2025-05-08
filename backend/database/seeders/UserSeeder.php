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
        User::create([
            'first_name' => 'Admin',
            'last_name_1' => 'HostControl',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'password' => 'password',
            'birthdate' => '1990-01-15',
            'address' => '123 Main Street, Sample City',
            'document_type' => User::DOCUMENT_DNI,
            'document_number' => '12345678Z',
            'phone' => '+34 600 123 456',
            'role' => User::ROLE_ADMIN,
            'comments' => 'Seeder-generated admin for testing'
        ]);
        User::create([
            'first_name' => 'User',
            'last_name_1' => 'Hostcontrol',
            'email' => 'user@email.com',
            'email_verified_at' => now(),
            'password' => 'password',
            'birthdate' => '1990-01-15',
            'address' => '123 Main Street, Sample City',
            'document_type' => User::DOCUMENT_DNI,
            'document_number' => '12345678P',
            'phone' => '+34 600 123 456',
            'role' => User::ROLE_USER,
            'comments' => 'Seeder-generated user for testing'
        ]);
        User::factory()->count(3)->create(['role' => User::ROLE_ADMIN]);
        User::factory()->count(30)->create(['role' => User::ROLE_USER]);
    }
}
