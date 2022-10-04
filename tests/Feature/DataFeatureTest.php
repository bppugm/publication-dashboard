<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Data;
use App\Models\User;
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
    public function user_can_create_data_with_user_id()
    {
        $this->login();
        $data = Data::factory()->make()->toArray();

        $response = $this->postJson(route('data.store'), $data);

        $response->assertCreated();
        $this->assertDatabaseHas('data', $data);
    }

    /** @test */
    public function user_can_update_data_user_id()
    {
        $this->login();
        $data = Data::factory()->create(['user_id' => null]);
        $update = ['user_id' => User::factory()->create()->id];

        $response = $this->putJson(route('data.update', $data), $update);

        $response->assertOk();
        $this->assertDatabaseHas('data', [
            'id' => $data->id,
            'user_id' => $update['user_id'],
        ]);
    }

    /** @test */
    // user can change data user_id to other user_id
    public function user_can_update_data_user_id_to_other_user_id()
    {
        $this->login();
        $data = Data::factory()->create(['user_id' => User::factory()->create()->id]);
        $update = ['user_id' => User::factory()->create()->id];

        $response = $this->putJson(route('data.update', $data), $update);

        $response->assertOk();
        $this->assertDatabaseHas('data', [
            'id' => $data->id,
            'user_id' => $update['user_id'],
        ]);
    }


    /** @test */
    // user can filter data by user_id
    public function user_can_filter_data_by_user_id()
    {
        $this->login();
        $user = User::factory()->create();
        Data::factory(2)->create(['user_id' => $user->id]);
        Data::factory(2)->create();
        // total 4 data, 2 have user_id, 2 don't have user_id

        $response = $this->getJson(route('data.index', [
            'user' => $user->id
        ]));

        $response->assertOk()->assertJsonCount(2, 'data');
    }

    /** @test */
    public function user_can_create_data_with_categories()
    {
        $this->login();
        $categories = Category::factory(2)->create();
        $data = Data::factory()->make()->only('name', 'description', 'notes');

        $response = $this->postJson(route('data.store'), array_merge($data, [
            'categories' => [$categories->first()->id, $categories->last()->id]
        ]));

        $response->assertCreated();
        $this->assertDatabaseHas('data', $data);
        $this->assertDatabaseHas('category_data', [
            'data_id' => $response->json('id'),
            'category_id' => $categories->first()->id
        ]);
        $this->assertDatabaseHas('category_data', [
            'data_id' => $response->json('id'),
            'category_id' => $categories->last()->id
        ]);
    }

    /** @test */
    public function user_can_update_data_with_categories()
    {
        $this->login();
        $categories = Category::factory(2)->create();
        $data = Data::factory()->create();
        $data->categories()->attach([$categories->last()->id, $categories->first()->id]);

        $response = $this->putJson(route('data.update', $data), [
            'categories' => [$categories->last()->id]
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('category_data', [
            'data_id' => $data->id,
            'category_id' => $categories->last()->id
        ]);
        $this->assertDatabaseMissing('category_data', [
            'data_id' => $data->id,
            'category_id' => $categories->first()->id
        ]);
    }

    /** @test */
    public function user_can_filter_data_by_category()
    {
        $this->login();
        $categories = Category::factory(2)->create();
        $data = Data::factory(5)->create();
        $data->first()->categories()->attach([$categories->first()->id, $categories->last()->id]);
        $data->last()->categories()->attach([$categories->first()->id]);

        $response = $this->getJson(route('data.index', [
            'categories' => [$categories->first()->name, $categories->last()->name]
        ]));

        $response->assertOk()->assertJsonCount(1, 'data');
    }

}
