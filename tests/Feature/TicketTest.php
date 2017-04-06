<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TicketTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function department_is_required_to_create_ticket()
    {
    	$user = factory('App\User')->create();

    	$ticket = [
    		'user_id' => $user->id,
    		'subject' => 'Testing 123.',
    		'details' => 'This is a details test.',
    		'priority' => 'Normal'
    	];

    	$response = $this->actingAs($user)->json('POST', '/tickets', $ticket);
        $response->assertStatus(422);
    }

    /** @test */
    function a_registered_user_can_create_a_ticket()
    {
        $user = factory('App\User')->create();

        $department = factory('App\Department')->create();

        $ticket = [
            'user_id' => $user->id,
            'department' => $department->id,
            'subject' => 'Testing 123.',
            'details' => 'This is a details test.',
            'priority' => 'Normal'
        ];

        $response = $this->actingAs($user)->json('POST', '/tickets', $ticket);
        $response->assertStatus(200);
    }

    /** @test */
    function a_registered_user_can_view_their_ticket()
    {
        $user = factory('App\User')->create();

        $department = factory('App\Department')->create();

        $params = [
            'user_id' => $user->id,
            'department' => $department->id,
            'subject' => 'Testing 123.',
            'details' => 'This is a details test.',
            'priority' => 'Normal'
        ];

        $this->actingAs($user)->json('POST', '/tickets', $params);

        $response = $this->actingAs($user)->json('GET', '/tickets/1');
        $response->assertStatus(200);
    }

    /** @test */
    function a_registered_user_cant_view_another_users_ticket()
    {
        $tickets = factory('App\Ticket', 5)->create();

        $user = factory('App\User')->create();

        $response = $this->actingAs($user)->json('GET', '/tickets/1');
        $response->assertStatus(404);
    }

    /** @test */
    function a_staff_can_view_any_users_ticket_but_users_cant_even_using_staff_path()
    {
        $tickets = factory('App\Ticket', 5)->create();

        $user = factory('App\User')->create();
        $userStaff = factory('App\User')->create(['role' => 'staff']);

        $response = $this->actingAs($userStaff)->json('GET', '/tickets/staff/1');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->json('GET', '/tickets/staff/1');
        $response->assertStatus(404);
    }
}
