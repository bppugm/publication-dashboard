<?php

namespace Tests\Feature\Models;

use App\Models\Dashboard;
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
}
