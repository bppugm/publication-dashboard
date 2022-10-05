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
    public function admin_can_store_a_data()
    {
        $this->login();
        $data = Data::factory()->make()->only('name', 'description', 'notes');

        $response = $this->postJson(route('data.store'), $data);

        $response->assertCreated();
        $this->assertDatabaseHas('data', $data);
    }

    /** @test */
    public function admin_can_update_a_data()
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
    public function admin_can_delete_a_data()
    {
        $this->login();
        $data = Data::factory()->create();

        $response = $this->deleteJson(route('data.destroy', $data));

        $response->assertNoContent();
        $this->assertDeleted($data);
    }

    /** @test */
    // admin can search data
    public function admin_can_search_data()
    {
        $this->login();
        $data = Data::factory(5)->create()->unique();
        $search = $data->first()->name;

        $response = $this->getJson(route('data.index', ['search' => $search]));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }

    /** @test */
    // user can not store/create a data
    public function user_can_not_store_a_data()
    {
        $this->login(User::factory()->create());
        $data = Data::factory()->make()->only('name', 'description', 'notes');

        $response = $this->postJson(route('data.store'), $data);

        $response->assertForbidden();
        $this->assertDatabaseMissing('data', $data);
    }

    /** @test */
    // user can update any data except for user_id
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
    // user can not delete a data
    public function user_can_not_delete_a_data()
    {
        $this->login(User::factory()->create());
        $data = Data::factory()->create();

        $response = $this->deleteJson(route('data.destroy', $data));

        $response->assertForbidden();
        $this->assertDatabaseHas('data', $data->only('id'));
    }

    /** @test */
    public function user_can_search_data()
    {
        $user = User::factory()->create();
        $this->login($user);
        Data::factory(2)->create(['name' => 'somethings']);
        Data::factory(2)->create();

        $response = $this->getJson(route('data.index', [
            'search' => 'somethings'
        ]));

        $response->assertOk()->assertJsonCount(2, 'data');
    }

    /** @test */
    public function admin_can_create_data_with_user_id()
    {
        $this->login();
        $data = Data::factory()->make()->toArray();

        $response = $this->postJson(route('data.store'), $data);

        $response->assertCreated();
        $this->assertDatabaseHas('data', $data);
    }

    /** @test */
    public function admin_can_update_data_user_id()
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
    public function admin_can_update_data_user_id_to_other_user_id()
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
    public function admin_can_filter_data_by_user_id()
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
    public function admin_can_create_data_with_categories()
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
    // admin can update data categories
    public function admin_can_update_data_categories()
    {
        $this->login();
        $data = Data::factory()->create();
        $categories = Category::factory(2)->create();

        $response = $this->putJson(route('data.update', $data), [
            'categories' => [$categories->first()->id, $categories->last()->id]
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('category_data', [
            'data_id' => $data->id,
            'category_id' => $categories->first()->id
        ]);
        $this->assertDatabaseHas('category_data', [
            'data_id' => $data->id,
            'category_id' => $categories->last()->id
        ]);
    }

    /** @test */
    // user can not update data user_id
    public function user_can_not_update_data_user_id()
    {
        $user = User::factory()->create();
        $this->login($user);
        $data = Data::factory()->create(['user_id' => null]);
        $update = ['user_id' => User::factory()->create()->id];

        $response = $this->putJson(route('data.update', $data), $update);

        $response->assertOk();
        $this->assertDatabaseMissing('data', [
            'id' => $data->id,
            'user_id' => $update['user_id'],
        ]);
        // response ok but user_id not updated
    }

    /** @test */
    // user can update data categories
    public function user_can_update_data_categories()
    {
        $user = User::factory()->create();
        $this->login($user);
        $data = Data::factory()->create();
        $categories = Category::factory(2)->create();
        $data->categories()->attach($categories->first()->id);

        $response = $this->putJson(route('data.update', $data), [
            'categories' => [$categories->first()->id, $categories->last()->id]
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('category_data', [
            'data_id' => $data->id,
            'category_id' => $categories->first()->id
        ]);
        $this->assertDatabaseHas('category_data', [
            'data_id' => $data->id,
            'category_id' => $categories->last()->id
        ]);
    }

    /** @test */
    public function user_can_filter_data_by_category()
    {
        $user = User::factory()->create();
        $this->login($user);
        $categories = Category::factory(2)->create();
        $data = Data::factory(5)->create();
        $data->first()->categories()->attach([$categories->first()->id, $categories->last()->id]);
        $data->last()->categories()->attach([$categories->first()->id]);

        $response = $this->getJson(route('data.index', [
            'categories' => [$categories->first()->name, $categories->last()->name]
        ]));

        $response->assertOk()->assertJsonCount(1, 'data');
    }

    /** @test */
    public function user_can_filter_data_assigned_to_self()
    {
        $user = User::factory()->create();
        $this->login($user);
        Data::factory(2)->create(['user_id' => $user->id]);
        Data::factory(2)->create(); //create additional 2 data without user_id

        $response = $this->getJson(route('data.index', [
            'me' => $user->id,
        ]));

        $response->assertOk()->assertJsonCount(2, 'data');
    }

    /** @test */
    public function user_can_not_filter_data_assigned_to_other_user_by_user()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        Data::factory(2)->create(['user_id' => $user2->id]);
        Data::factory(2)->create(); //create additional 2 data without user_id
        $this->login($user);

        $response = $this->getJson(route('data.index', [
            'user' => $user2->id,
        ]));

        $response->assertOk()->assertJsonCount(4, 'data');
        // will return all data because user can't filter data by user endpoint
    }

    /** @test */
    public function user_can_not_filter_data_assigned_to_other_user_by_me()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        Data::factory(2)->create(['user_id' => $user2->id]);
        Data::factory(2)->create(['user_id' => $user->id]);
        $this->login($user);

        $response = $this->getJson(route('data.index', [
            'me' => $user2->id,
        ]));

        $response->assertOk()->assertJsonCount(2, 'data');
        // will return only data assigned to user because filter only by user_id that logged in
    }
}
