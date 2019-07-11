<?php

namespace App\Http\Controllers;

use App\Task;
use App\Event;
use Illuminate\Http\Request;

class EventTasksController extends Controller
{
    public function store(Event $event)
    {
        $this->authorize('update', $event);

        request()->validate([
            'body' => 'required'
        ]);

        $event->addTask(request('body'));

        return redirect($event->path());
    }

    public function update(Event $event, Task $task)
    {
        $this->authorize('update', $event);

        $attributes = request()->validate([
            'body' => 'required'
        ]);

        $task->update($attributes);

        request('completed') ? $task->complete() : $task->incomplete();

        return redirect($event->path());
    }
}
