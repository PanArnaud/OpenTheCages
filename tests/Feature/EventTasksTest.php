<?php

namespace Tests\Feature;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\EventFactory;

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
        $event = EventFactory::create();

        $this->actingAs($event->owner)
            ->post($event->path() . '/tasks', ['body' => 'Test Task']);
        
        $this->get($event->path())
            ->assertSee('Test Task');
    }
    
    /** @test */
    public function a_task_can_be_updated()
    {
        $event = EventFactory::withTasks(1)->create();

        $this->actingAs($event->owner)
            ->patch($event->tasks->first()->path(), [
                'body' => 'changed'
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed'
        ]);
    }

    /** @test */
    public function a_task_can_be_completed()
    {
        $event = EventFactory::withTasks(1)->create();

        $this->actingAs($event->owner)
            ->patch($event->tasks->first()->path(), [
                'body' => 'changed',
                'completed' => true
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    /** @test */
    public function a_task_can_be_marked_as_incomplete()
    {
        $event = EventFactory::withTasks(1)->create();

        $this->actingAs($event->owner)
            ->patch($event->tasks->first()->path(), [
                'body' => 'changed',
                'completed' => true
            ]);

        $this->actingAs($event->owner)
            ->patch($event->tasks->first()->path(), [
                'body' => 'changed',
                'completed' => false
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => false
        ]);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();

        $event = EventFactory::withTasks(1)->create();

        $this->patch($event->tasks->first()->path(), ['body' => 'Another body'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Another body']);
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $event = EventFactory::create();

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->actingAs($event->owner)
            ->post($event->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }
}
