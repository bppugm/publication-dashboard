<?php

namespace Tests\Feature\Models;

use App\Models\Dashboard;
use App\Models\Data;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DataModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_using_factory()
    {
        $data = Data::factory()->make()->toArray();
        Data::factory()->create($data);

        $this->assertDatabaseHas('data', $data);
    }

    /** @test */
    public function it_belongs_to_many_dashboards()
    {
        $data = Data::factory()->create();
        $dashboard = Dashboard::factory(5)->create();
        $data->dashboards()->attach($dashboard);

        $this->assertCount(5, $data->dashboards);
    }

    /** @test */
    public function it_logs_activity()
    {
        $data = Data::factory()->create();

        $this->assertInstanceOf(\Spatie\Activitylog\Models\Activity::class, $data->activities->first());
    }

    /** @test */
    public function it_logs_updated_activity()
    {
        $data = Data::factory()->create();

        $data->update(['name' => 'updated']);

        $this->assertCount(1, $data->activities()->where('description', 'updated')->get());
    }
}
