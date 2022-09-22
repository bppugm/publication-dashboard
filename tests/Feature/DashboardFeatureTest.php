<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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

    /** @test */
    public function user_can_update_dashboard()
    {
        $this->login();
        $dashboard = Dashboard::factory()->create();

        $response = $this->putJson(route('dashboard.update', $dashboard->id), [
            'name' => 'New name',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('dashboards', [
            'id' => $dashboard->id,
            'name' => 'New name',
        ]);
    }
    
    public function user_can_create_dashboard_with_description()
    {
        $this->login();
        $data = Dashboard::factory()->make([
            'description' => "Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur,"
        ])->toArray();

        $response = $this->postJson(route('dashboard.store'), $data);

        $response->assertCreated();
    }
}
