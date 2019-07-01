<?php

namespace Tests\Feature;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_event_can_have_tasks()
    {
        $this->signIn();

        $event = factory(Event::class)->create(['owner_id' => auth()->id()]);
    
        $body = 'Test Task';
        $this->post($event->path() . '/tasks', ['body' => $body]);

        $this->get($event->path())
            ->assertSee($body);
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();

        $event = factory(Event::class)->create(['owner_id' => auth()->id()]);

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($event->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }
}
