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
    public function guests_cannot_add_tasks_to_event()
    {
        $event = factory('App\Event')->create();

        $this->post($event->path() . '/tasks')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $event = factory('App\Event')->create();
    
        $this->post($event->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test case']);
    }

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
    public function a_task_can_be_updated()
    {
        $this->signIn();
        
        $event = factory(Event::class)->create(['owner_id' => auth()->id()]);
    
        $task = $event->addTask('Test task');

        $this->patch($task->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();

        $event = factory('App\Event')->create();
    
        $task = $event->addTask('test task');

        $this->patch($task->path(), ['body' => 'Another body'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Another body']);
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
