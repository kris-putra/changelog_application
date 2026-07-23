<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TechnicalComponent;

class TechnicalComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            'Database',
            'Backend',
            'Frontend',
            'API',
            'Authentication',
            'Middleware',
            'Configuration',
            'Deployment',
            'Testing',
            'Documentation',
        ];

        foreach ($components as $name) {
            TechnicalComponent::updateOrCreate(
                ['name' => $name],
                ['name' => $name]
            );
        }
    }
}