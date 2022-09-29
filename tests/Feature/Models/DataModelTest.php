<?php

namespace Tests\Feature\Models;

use App\Models\Category;
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
    public function it_belongs_to_many_categories()
    {
        $data = Data::factory()->create();
        $category = Category::factory(5)->create();
        $data->categories()->attach($category);

        $this->assertCount(5, $data->categories);
    }

    /** @test */
    public function it_can_be_filtered_by_categories()
    {
        $category = Category::factory()->create();
        $data = Data::factory(5)->create();
        $data->first()->categories()->attach($category);

        $filtered = Data::filter(['categories' => [$category->name]])->get();

        $this->assertCount(1, $filtered);
    }

    /** @test */
    // it can be filtered using many categories
    public function it_can_be_filtered_by_many_categories()
    {
        $categories = Category::factory(2)->create();
        $data = Data::factory(5)->create();
        $data->first()->categories()->attach([$categories->first()->id, $categories->last()->id]);
        $data->last()->categories()->attach([$categories->last()->id]);

        $filtered = Data::filter(['categories' => [$categories->first()->name, $categories->last()->name]])->get();

        $this->assertCount(1, $filtered);
    }
}
