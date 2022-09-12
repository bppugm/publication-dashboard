<?php

namespace Tests\Feature;

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
}
