<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create the requested first user `gaia` (username-based login)
        User::factory()->create([
            'name' => 'gaia',
            'email' => 'gaia@example.com',
            'password' => bcrypt('adminaplikasi0'),
        ]);
    }
}
