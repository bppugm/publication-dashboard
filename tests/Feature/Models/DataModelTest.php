<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Dashboard;
use App\Models\Data;
use App\Models\User;
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
        $user = User::factory()->create();
        $this->login($user);
        $category = Category::factory()->create();
        $data = Data::factory(5)->create();
        $data->first()->categories()->attach($category);

        $filtered = Data::filter(['categories' => [$category->name]])->get();

        $this->assertCount(1, $filtered);
    }

    /** @test */
    public function it_can_be_filtered_by_many_categories()
    {
        $user = User::factory()->create();
        $this->login($user);
        $categories = Category::factory(2)->create();
        $data = Data::factory(5)->create();
        $data->first()->categories()->attach([$categories->first()->id, $categories->last()->id]);
        $data->last()->categories()->attach([$categories->last()->id]);

        $filtered = Data::filter(['categories' => [$categories->first()->name, $categories->last()->name]])->get();

        $this->assertCount(1, $filtered);
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

    /** @test */
    public function it_belongs_to_a_user()
    {
        $data = Data::factory()->create([
            'user_id' => User::factory()
        ]);

        $this->assertInstanceOf(\App\Models\User::class, $data->user);
    }

    /** @test */
    // it can be filtered by user
    public function it_can_be_filtered_by_user()
    {
        $this->login();
        $user = \App\Models\User::factory()->create();
        $data = Data::factory(5)->create();
        $data->first()->update(['user_id' => $user->id]);

        $filtered = Data::filter(['user' => $user->id])->get();

        $this->assertCount(1, $filtered);
    }

}
