<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Keep an example admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create the requested first user `gaia` (username-based login)
        User::factory()->create([
            'name' => 'gaia',
            'email' => 'gaia@example.com',
            'password' => bcrypt('adminaplikasi0'),
        ]);
    }
}
