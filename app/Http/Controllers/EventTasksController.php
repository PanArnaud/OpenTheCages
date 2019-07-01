<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventTasksController extends Controller
{
    public function store(Event $event)
    {
        request()->validate([
            'body' => 'required'
        ]);

        $event->addTask(request('body'));

        return redirect($event->path());
    }
}
