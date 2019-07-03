<?php

namespace App\Http\Controllers;

use App\Event;

class EventsController extends Controller
{
    public function index()
    {
        $events = auth()->user()->events;

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $this->authorize('update', $event);

        return view('events.show', compact('event'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'title' => 'required', 
            'description' => 'required',
            'notes' => ''
        ]);

        $event = auth()->user()->events()->create($attributes);

        return redirect($event->path());
    }

    public function update(Event $event)
    {
        $this->authorize('update', $event);

        $event->update(request(['notes']));

        return  redirect($event->path());
    }
}
