<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\EventFactory;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_an_event()
    {
        $event = EventFactory::create();

        $this->assertCount(1, $event->activity);
        $this->assertEquals('created', $event->activity[0]->description);
    }

    /** @test */
    public function updating_an_event()
    {
        $event = EventFactory::create();

        $event->update(['title' => 'changed']);

        $this->assertCount(2, $event->activity);
        $this->assertEquals('updated', $event->activity->last()->description);
    }
    
    /** @test */
    public function creating_a_new_task()
    {
        $event = EventFactory::withTasks(1)->create();

        $this->assertCount(2, $event->activity);
        $this->assertEquals('created_task', $event->activity->last()->description);
    }

    /** @test */
    public function completing_a_task()
    {
        $event = EventFactory::withTasks(1)->create();
        
        $this->actingAs($event->owner)->patch($event->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => true
        ]);

        $this->assertCount(3, $event->activity);
        $this->assertEquals('completed_task', $event->activity->last()->description);
    }

    /** @test */
    public function incompleting_a_task()
    {
        $event = EventFactory::withTasks(1)->create();
        
        $this->actingAs($event->owner)->patch($event->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => true
        ]);

        $this->assertCount(3, $event->activity);
        
        $this->actingAs($event->owner)->patch($event->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => false
        ]);

        $event->refresh();

        $this->assertCount(4, $event->activity);
        $this->assertEquals('incompleted_task', $event->activity->last()->description);
    }

    /** @test */
    public function deleting_a_task()
    {
        $event = EventFactory::withTasks(1)->create();
        
        $event->tasks[0]->delete();

        $this->assertCount(3, $event->activity);
        $this->assertEquals('deleted_task', $event->activity->last()->description);
    }
}
