<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\EventFactory;

class InvitationTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function an_event_can_invite_a_user()
    {
        $event = EventFactory::create();


        $event->invite($newUser = factory(User::class)->create());

        $this->signIn($newUser);
        $this->post(action('EventTasksController@store', $event), $task = ['body' => 'Foo task']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
