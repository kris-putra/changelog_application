<?php

namespace Tests\Feature;

use App\Models\FeatureRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_displays_feature_request_listing(): void
    {
        FeatureRequest::factory()->create([
            'title' => 'Integrasi webhook billing',
            'description' => 'Perlu integrasi untuk webhook billing',
            'type' => 'feature',
            'priority' => 'high',
            'status' => 'pending',
            'requested_by' => 1,
        ]);

        $response = $this->get(route('feature-requests.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Permintaan');
        $response->assertSee('Integrasi webhook billing');
    }

    public function test_user_can_create_a_feature_request(): void
    {
        $response = $this->post(route('feature-requests.store'), [
            'title' => 'Dashboard KPI tim engineering',
            'description' => 'Butuh dashboard KPI untuk tim engineering.',
            'type' => 'feature',
            'priority' => 'medium',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('feature_requests', [
            'title' => 'Dashboard KPI tim engineering',
            'status' => 'pending',
            'requested_by' => 1,
        ]);
    }
}
