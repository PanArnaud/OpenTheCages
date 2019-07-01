<?php

namespace Tests\Feature;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageEventsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_manage_events()
    {
        $event = factory('App\Event')->create();

        $this->get('/events')->assertRedirect('login');
        $this->get($event->path())->assertRedirect('login');
        $this->get('/events/create')->assertRedirect('login');
        $this->post('/events', $event->toArray())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_an_event()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $this->get('/events/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $response = $this->post('/events', $attributes);

        $response->assertRedirect(Event::where($attributes)->first()->path());

        $this->assertDatabaseHas('events', $attributes);

        $this->get('/events')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_view_their_event()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $event = factory('App\Event')->create(['owner_id' => auth()->id()]);

        $this->get($event->path())
            ->assertSee($event->title)
            ->assertSee(\Illuminate\Support\Str::limit($event->description, 100));
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_events_of_others()
    {
        $this->signIn();

        $event = factory('App\Event')->create();

        $this->get($event->path())
            ->assertStatus(403);
    }

    /** @test */
    public function an_event_requires_a_title()
    {
        $this->signIn();

        $attributes = factory('App\Event')->raw(['title' => '']);

        $this->post('/events', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_event_requires_a_description()
    {
        $this->signIn();

        $attributes = factory('App\Event')->raw(['description' => '']);

        $this->post('/events', $attributes)->assertSessionHasErrors('description');
    }
}
