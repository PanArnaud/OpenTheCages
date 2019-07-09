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
        $event = auth()->user()->events()->create($this->validateRequest());

        return redirect($event->path());
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    public function update(Event $event)
    {
        $this->authorize('update', $event);

        $event->update($this->validateRequest());

        return  redirect($event->path());
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'sometimes|required', 
            'description' => 'sometimes|required',
            'notes' => 'nullable'
        ]);
    }
}
