<?php

namespace Tests\Feature;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\EventFactory;

class ManageEventsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_manage_events()
    {
        $event = factory('App\Event')->create();

        $this->get('/events')->assertRedirect('login');
        $this->get($event->path())->assertRedirect('login');
        $this->get($event->path().'/edit')->assertRedirect('login');
        $this->get('/events/create')->assertRedirect('login');
        $this->post('/events', $event->toArray())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_an_event()
    {
        $this->signIn();

        $this->get('/events/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'This is a note.'
        ];

        $response = $this->post('/events', $attributes);

        $event = Event::where($attributes)->first();

        $response->assertRedirect($event->path());

        $this->get($event->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function an_user_can_update_an_event()
    {
        $event = EventFactory::create();

        $this->actingAs($event->owner)
            ->patch($event->path(), $attributes = [
                'title' => 'changed',
                'description' => 'changed',
                'notes' => 'changed'
            ])->assertRedirect($event->path());

        $this->get($event->path().'/edit')->assertOk();

        $this->assertDatabaseHas('events', $attributes);
    }

    /** @test */
    public function a_user_can_update_an_event_general_notes()
    {
        $event = EventFactory::create();

        $this->actingAs($event->owner)
            ->patch($event->path(), $attributes = [
                'notes' => 'changed'
            ])->assertRedirect($event->path());

        $this->assertDatabaseHas('events', $attributes);
    }

    /** @test */
    public function a_user_can_view_their_events()
    {
        $event = EventFactory::create();

        $this->actingAs($event->owner)
            ->get($event->path())
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
    public function an_authenticated_user_cannot_update_the_events_of_others()
    {
        $this->signIn();

        $event = factory('App\Event')->create();

        $this->patch($event->path())
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
    
    /** @test */
    public function unauthorized_users_cannot_delete_an_event()
    {
        $event = EventFactory::create();

        $this->delete($event->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($event->path())->assertStatus(403);
    }

    /** @test */
    public function an_user_can_delete_an_event()
    {
        $event = EventFactory::create();

        $this->actingAs($event->owner)
            ->delete($event->path())->assertRedirect('/events');

        $this->assertDatabaseMissing('events', $event->only('id'));
    }
}
