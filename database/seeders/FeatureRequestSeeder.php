<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeatureRequest;

class FeatureRequestSeeder extends Seeder
{
    public function run()
    {
        FeatureRequest::factory()->count(10)->create();
    }
}
