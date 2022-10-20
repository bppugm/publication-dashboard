<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
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

    /** @test */
    public function user_can_create_dashboard_with_description()
    {
        $this->login();
        $data = Dashboard::factory()->make([
            'description' => "Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur,"
        ])->toArray();

        $response = $this->postJson(route('dashboard.store'), $data);

        $response->assertCreated();
    }

    /** @test */
    public function user_can_activate_dashboard_and_automatically_ordering()
    {
        $this->login();
        $dashboard1 = Dashboard::factory()->create();
        $dashboard2 = Dashboard::factory()->create();
        $dashboard3 = Dashboard::factory()->create();

        // activate dashboard 1
        $response = $this->postJson(route('dashboard.activation'), [
            'id' => $dashboard1->id,
        ]);
        // activate dashboard 2
        $response = $this->postJson(route('dashboard.activation'), [
            'id' => $dashboard2->id,
        ]);
        // activate dashboard 3
        $response = $this->postJson(route('dashboard.activation'), [
            'id' => $dashboard3->id,
        ]);

        // check the order
        $this->assertDatabaseHas('dashboards', [
            'id' => $dashboard1->id,
            'order' => 1
        ]);
        $this->assertDatabaseHas('dashboards', [
            'id' => $dashboard2->id,
            'order' => 2
        ]);
        $this->assertDatabaseHas('dashboards', [
            'id' => $dashboard3->id,
            'order' => 3
        ]);
    }

    /** @test */
    public function user_can_deactivate_dashboard_and_automatically_ordering()
    {
        $this->login();
        $dashboard1 = Dashboard::factory()->create([
            'order' => 1
        ]);
        $dashboard2 = Dashboard::factory()->create([
            'order' => 2
        ]);
        $dashboard3 = Dashboard::factory()->create([
            'order' => 3
        ]);

        // deactivate dashboard 2
        $response = $this->delete(route('dashboard.deactivation', $dashboard2->id));

        // check the order
        $this->assertDatabaseHas('dashboards', [
            'id' => $dashboard1->id,
            'order' => 1
        ]);
        $this->assertDatabaseHas('dashboards', [
            'id' => $dashboard2->id,
            'order' => 0
        ]);
        $this->assertDatabaseHas('dashboards', [
            'id' => $dashboard3->id,
            'order' => 2
        ]);
    }

    /** @test */
    public function user_can_update_dashboard_order()
    {
        $this->login();
        $dashboard1 = Dashboard::factory()->create([
            'order' => 1
        ]);
        $dashboard2 = Dashboard::factory()->create([
            'order' => 2
        ]);
        $dashboard3 = Dashboard::factory()->create([
            'order' => 3
        ]);

        $data = [
            ['id' => $dashboard1->id, 'order' => 2],
            ['id' => $dashboard2->id, 'order' => 3],
            ['id' => $dashboard3->id, 'order' => 1],
        ];

        $response = $this->putJson(route('dashboard.order'), $data);

        $response->assertNoContent();
        $this->assertDatabaseHas('dashboards', [
            'id' => $dashboard1->id,
            'order' => 2
        ]);
    }

    /** @test */
    public function it_dispatches_dashboard_updated_on_update_dashboard()
    {
        Event::fake(\App\Events\DashboardUpdated::class);
        $this->login();
        $dashboard = Dashboard::factory()->create();

        $response = $this->putJson(route('dashboard.update', $dashboard->id), [
            'name' => 'New name',
        ]);

        $response->assertStatus(200);
        Event::assertDispatched(\App\Events\DashboardUpdated::class);
    }

    /** @test */
    public function it_dispatches_dashboard_activation_updated_on_activate_dashboard()
    {
        Event::fake(\App\Events\DashboardActivationUpdated::class);
        $this->login();
        $dashboard = Dashboard::factory()->create();

        $response = $this->postJson(route('dashboard.activation'), [
            'id' => $dashboard->id,
        ]);

        $response->assertStatus(200);
        Event::assertDispatched(\App\Events\DashboardActivationUpdated::class);
    }

    /** @test */
    public function it_dispatches_dashboard_activation_updated_on_deactivate_dashboard()
    {
        Event::fake(\App\Events\DashboardActivationUpdated::class);
        $this->login();
        $dashboard = Dashboard::factory()->create();

        $response = $this->delete(route('dashboard.deactivation', $dashboard->id));

        $response->assertStatus(200);
        Event::assertDispatched(\App\Events\DashboardActivationUpdated::class);
    }

    /** @test */
    public function it_dispatches_dashboard_activation_updated_on_update_dashboard_order()
    {
        Event::fake(\App\Events\DashboardActivationUpdated::class);
        $this->login();
        $dashboard1 = Dashboard::factory()->create([
            'order' => 1
        ]);
        $dashboard2 = Dashboard::factory()->create([
            'order' => 2
        ]);
        $dashboard3 = Dashboard::factory()->create([
            'order' => 3
        ]);

        $data = [
            ['id' => $dashboard1->id, 'order' => 2],
            ['id' => $dashboard2->id, 'order' => 3],
            ['id' => $dashboard3->id, 'order' => 1],
        ];

        $response = $this->putJson(route('dashboard.order'), $data);

        $response->assertNoContent();
        Event::assertDispatched(\App\Events\DashboardActivationUpdated::class);
    }
}
