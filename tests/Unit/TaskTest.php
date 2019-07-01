<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_an_event()
    {
        $task = factory('App\Task')->create();

        $this->assertInstanceOf('App\Event', $task->event);
    }

    /** @test */
    public function it_has_a_path()
    {
        $task = factory('App\Task')->create();

        $this->assertEquals("/events/{$task->event->id}/tasks/{$task->id}", $task->path());
    }
}
