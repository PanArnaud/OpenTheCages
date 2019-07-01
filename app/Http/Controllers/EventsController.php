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
        if (auth()->user()->isNot($event->owner)) {
            abort(403);
        }

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
            'description' => 'required'
        ]);

        auth()->user()->events()->create($attributes);

        return redirect('/events');
    }
}
