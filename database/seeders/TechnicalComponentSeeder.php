<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TechnicalComponent;

class TechnicalComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            ['name' => 'Database', 'display_order' => 1],
            ['name' => 'Backend', 'display_order' => 2],
            ['name' => 'Frontend', 'display_order' => 3],
            ['name' => 'API', 'display_order' => 4],
            ['name' => 'Authentication', 'display_order' => 5],
            ['name' => 'Middleware', 'display_order' => 6],
            ['name' => 'Configuration', 'display_order' => 7],
            ['name' => 'Deployment', 'display_order' => 8],
            ['name' => 'Testing', 'display_order' => 9],
            ['name' => 'Documentation', 'display_order' => 10],
        ];

        foreach ($components as $component) {
            TechnicalComponent::updateOrCreate(
                ['name' => $component['name']],
                ['display_order' => $component['display_order']]
            );
        }
    }
}