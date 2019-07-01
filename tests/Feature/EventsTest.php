<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_create_events()
    {
        $attributes = factory('App\Event')->raw(['owner_id' => null]);

        $this->post('/events', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_view_events()
    {
        $this->get('/events')->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_view_a_single_event()
    {
        $event = factory('App\Event')->create();

        $this->get($event->path())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_an_event()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory('App\User')->create());

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/events', $attributes)->assertRedirect('/events');

        $this->assertDatabaseHas('events', $attributes);

        $this->get('/events')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_view_their_event()
    {
        $this->be(factory('App\User')->create());

        $this->withoutExceptionHandling();

        $event = factory('App\Event')->create(['owner_id' => auth()->id()]);

        $this->get($event->path())
            ->assertSee($event->title)
            ->assertSee($event->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_events_of_others()
    {
        $this->be(factory('App\User')->create());

        $event = factory('App\Event')->create();

        $this->get($event->path())
            ->assertStatus(403);
    }

    /** @test */
    public function an_event_requires_a_title()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Event')->raw(['title' => '']);

        $this->post('/events', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_event_requires_a_description()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Event')->raw(['description' => '']);

        $this->post('/events', $attributes)->assertSessionHasErrors('description');
    }
}
