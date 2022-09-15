<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_dashboard()
    {
        $this->login();
        $data = Dashboard::factory()->make()->toArray();

        $response = $this->postJson(route('dashboard.store'), $data);

        $response->assertCreated();
    }

    /** @test */
    public function user_can_delete_dashboard()
    {
        $this->login();
        $dashboard = Dashboard::factory()->create();

        $response = $this->deleteJson(route('dashboard.destroy', $dashboard->id));

        $response->assertNoContent();
        $this->assertDeleted($dashboard);
    }
}
