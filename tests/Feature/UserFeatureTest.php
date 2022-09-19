<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_get_users_data()
    {
        $this->login();

        $response = $this->getJson(route('user.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function regular_user_is_forbidden_to_get_user_data()
    {
        $user = User::factory()->create();
        $this->login($user);

        $response = $this->getJson(route('user.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_can_not_get_user_data()
    {
        $response = $this->getJson(route('user.index'));

        $response->assertStatus(401);
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $this->login();
        $user = User::factory()->create();

        $response = $this->deleteJson(route('user.destroy', $user));

        $response->assertStatus(204);
    }

    /** @test */
    public function admin_can_add_user()
    {
        $this->login();

        $response = $this->postJson(route('user.store'), [
            'name' => 'admin3',
            'email' => 'admin3@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function admin_can_update_user()
    {
        $this->login();

        $user = User::factory()->create();

        $response = $this->putJson(route('user.update', $user), [
            'name' => 'admin3',
            'email' => 'admin4@mail.com',
            'password' => 'password4',
            'password_confirmation' => 'password4',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => 'admin3',
            'email' => 'admin4@mail.com'
        ]);
    }

    /** @test */
    public function admin_can_update_user_name_only()
    {
        $this->login();
        $user = User::factory()->create();

        $response = $this->putJson(route('user.update', $user), [
            'name' => 'admin3',
            //'email' => 'admin4@mail.com',
            //'password' => 'password4',
            //'password_confirmation' => 'password4',
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => 'admin3',
            'email' => $user->email
        ]);
    }

    /** @test */
    public function admin_can_update_user_email_only()
    {
        $this->login();
        $user = User::factory()->create();

        $response = $this->putJson(route('user.update', $user), [
            //'name' => 'admin3',
            'email' => 'admin4@mail.com',
            //'password' => 'password4',
            //'password_confirmation' => 'password4',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => 'admin4@mail.com'
        ]);
    }

    /** @test */
    public function admin_can_update_user_password_only()
    {
        $this->login();
        $user = User::factory()->create();

        $response = $this->putJson(route('user.update', $user), [
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200);
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }

    /** @test */
    public function admin_can_not_delete_themself()
    {
        $this->login();

        $response = $this->deleteJson(route('user.destroy', $this->user));

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id
        ]);
    }
}
