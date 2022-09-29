<?php

namespace Tests\Feature;

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
    public function user_can_update_user_id()
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
}
