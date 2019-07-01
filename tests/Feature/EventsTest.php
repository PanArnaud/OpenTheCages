<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_an_event()
    {
        $this->withoutExceptionHandling();
        
        $attributes = factory('App\Event')->raw();

        $this->post('/events', $attributes)->assertRedirect('/events');
        
        $this->assertDatabaseHas('events', $attributes);
        
        $this->get('/events')->assertSee($attributes['title']);
    }
    
    /** @test */
    public function a_user_can_view_the_event()
    {
        $this->withoutExceptionHandling();

        $event = factory('App\Event')->create();

        $this->get($event->path())
            ->assertSee($event->title)
            ->assertSee($event->description);
    }

    /** @test */
    public function an_event_requires_a_title()
    {
        $attributes = factory('App\Event')->raw(['title' => '']);

        $this->post('/events', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_event_requires_a_description()
    {
        $attributes = factory('App\Event')->raw(['description' => '']);

        $this->post('/events', $attributes)->assertSessionHasErrors('description');
    }
}
