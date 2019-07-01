<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $event = factory('App\Event')->create();

        $this->assertEquals('/events/' . $event->id, $event->path());
    }

    /** @test */
    public function it_belongs_to_an_owner()
    {
        $event = factory('App\Event')->create();

        $this->assertInstanceOf('App\User', $event->owner);
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $event = factory('App\Event')->create();

        $task = $event->addTask('Test task');

        $this->assertCount(1, $event->tasks);
        $this->assertTrue($event->tasks->contains($task));
    }
}
