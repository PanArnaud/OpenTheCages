<?php

namespace App\Observers;

use App\Event;

class EventObserver
{
    /**
     * Handle the event "created" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        $event->recordActivity('created');
    }

    public function updating(Event $event)
    {
        $event->old = $event->getOriginal();
    }

    /**
     * Handle the event "updated" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        $event->recordActivity('updated');
    }
}
