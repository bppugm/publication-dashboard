<?php

namespace Tests\Feature\Models;

use App\Models\Dashboard;
use App\Models\Data;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_using_factory()
    {
        $dashboard = Dashboard::factory()->create();

        $this->assertDatabaseHas('dashboards', [
            'id' => $dashboard->id
        ]);
    }

    /** @test */
    public function it_belongs_to_many_data()
    {
        $dashboard = Dashboard::factory()->create();
        $data = Data::factory(2)->create();

        $dashboard->data()->attach($data);

        $this->assertCount(2, $dashboard->data);
    }
}
