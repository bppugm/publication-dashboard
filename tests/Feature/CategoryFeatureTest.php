<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_store_a_category()
    {
        $this->login();
        $category = Category::factory()->make()->only('colour', 'name', 'description');

        $response = $this->postJson(route('category.store'), $category);

        $response->assertCreated();
        $this->assertDatabaseHas('categories', $category);
    }

    /** @test */
    public function user_can_update_a_category()
    {
        $this->login();
        $category = Category::factory()->create();

        $update = ['colour' => 'updated', 'name' => 'updated', 'description' => 'updated'];
        $response = $this->putJson(route('category.update', $category), $update);

        $response->assertOk();
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'colour' => $update['colour'],
            'name' => $update['name'],
            'description' => $update['description']
        ]);
    }

    /** @test */
    public function user_can_delete_a_category()
    {
        $this->login();
        $category = Category::factory()->create();

        $response = $this->deleteJson(route('category.destroy', $category));

        $response->assertNoContent();
        $this->assertDeleted($category);
    }

    /** @test */
    public function user_can_view_a_category()
    {
        $this->login();
        $category = Category::factory()->create();

        $response = $this->getJson(route('category.show', $category));

        $response->assertOk();
        $response->assertJson($category->toArray());
    }

}
