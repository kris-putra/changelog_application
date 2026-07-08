<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\FeatureRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_displays_feature_request_listing(): void
    {
        $user = User::factory()->create();

        FeatureRequest::factory()->create([
            'title' => 'Integrasi webhook billing',
            'description' => 'Perlu integrasi untuk webhook billing',
            'type' => 'feature',
            'priority' => 'high',
            'status' => 'pending',
            'requested_by' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('feature-requests.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Permintaan');
        $response->assertSee('Integrasi webhook billing');
    }

    public function test_user_can_create_a_feature_request(): void
    {
        $user = User::factory()->create();
        $application = Application::create([
            'name' => 'Changelog App',
            'description' => 'Aplikasi untuk changelog dan feature request.',
            'url' => 'https://changelog.test',
            'location' => 'Internal',
        ]);

        $response = $this->actingAs($user)->post(route('feature-requests.store'), [
            'application_id' => $application->id,
            'title' => 'Dashboard KPI tim engineering',
            'description' => 'Butuh dashboard KPI untuk tim engineering.',
            'detail_perubahan' => 'Detail perubahan test',
            'pemohon_perubahan' => 'John Doe',
            'as_is' => 'Sistem lama',
            'to_be' => 'Sistem baru',
            'klasifikasi_perubahan' => 'Normal',
            'type' => 'feature',
            'priority' => 'medium',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('feature_requests', [
            'title' => 'Dashboard KPI tim engineering',
            'application_id' => $application->id,
            'status' => 'pending',
            'requested_by' => $user->id,
        ]);
    }
}
