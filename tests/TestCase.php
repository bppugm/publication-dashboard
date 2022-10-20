<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Login as Superadmin
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function login($user = null)
    {
        $this->user = $user ?? User::factory()->superadmin()->create();

        $this->actingAs($this->user);
    }
}
