<?php

namespace Tests\Feature\Models;

use App\Models\Data;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_using_factory()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);
    }

    /** @test */
    public function it_has_many_data()
    {
        $user = User::factory()->has(Data::factory(5))->create();

        $this->assertCount(5, $user->data);
        $this->assertInstanceOf(\App\Models\Data::class, $user->data->first());
    }
}
