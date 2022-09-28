<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Data;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DataFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_store_a_data()
    {
        $this->login();
        $data = Data::factory()->make()->only('name', 'description', 'notes');

        $response = $this->postJson(route('data.store'), $data);

        $response->assertCreated();
        $this->assertDatabaseHas('data', $data);
    }

    /** @test */
    public function user_can_update_a_data()
    {
        $this->login();
        $data = Data::factory()->create();
        $update = ['name' => 'updated', 'value' => 300];

        $response = $this->putJson(route('data.update', $data), $update);

        $response->assertOk();
        $this->assertDatabaseHas('data', [
            'id' => $data->id,
            'name' => $update['name'],
            'value' => $update['value']
        ]);
    }

    /** @test */
    public function user_can_delete_a_data()
    {
        $this->login();
        $data = Data::factory()->create();

        $response = $this->deleteJson(route('data.destroy', $data));

        $response->assertNoContent();
        $this->assertDeleted($data);
    }

    /** @test */
    public function user_can_search_data()
    {
        $this->login();
        Data::factory(2)->create(['name' => 'somethings']);
        Data::factory(2)->create();

        $response = $this->getJson(route('data.index', [
            'search' => 'somethings'
        ]));

        $response->assertOk()->assertJsonCount(2, 'data');
    }

    /** @test */
    public function user_can_filter_data_by_category()
    {
        $this->login();
        $category = Category::factory()->create();
        $data = Data::factory(2)->create();
        $data->each(function ($data) use ($category) {
            $data->categories()->attach($category);
        });

        $response = $this->getJson(route('data.index', [
            'category' => $category->name
        ]));

        $response->assertOk()->assertJsonCount(2, 'data');
        $this->assertDatabaseHas('category_data', [
            'category_id' => $category->id,
            'data_id' => $data->pluck('id')->toArray()
        ]);
    }
}
