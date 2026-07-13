<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('slug', 'administrator')->first();

        // Update or create the first user `gaia` as administrator
        User::updateOrCreate(
            ['email' => 'gaia@example.com'],
            [
                'name' => 'gaia',
                'password' => bcrypt('adminaplikasi0'),
                'role_id' => $adminRole->id,
            ]
        );
    }
}
