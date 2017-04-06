<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_registered_user_can_login_and_see_their_dashboard()
    {
        $user = factory('App\User')->create();

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
    }

    /** @test */
    function a_registered_user_cant_see_the_staff_dashboard()
    {
        $user = factory('App\User')->create();

        $response = $this->actingAs($user)->get('/tickets');

        $response->assertStatus(404);
    }
}
