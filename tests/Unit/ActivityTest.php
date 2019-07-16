<?php

namespace Tests\Unit;

use App\Event;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\EventFactory;

class ActivityTest extends TestCase
{
    
    use RefreshDatabase;

    /** @test */
    function it_has_a_user()
    {
        $user = $this->signIn();

        $event = EventFactory:: ownedBy($user)->create();

        $this->assertEquals($user->id, $event->activity->first()->user->id);
    }
}
