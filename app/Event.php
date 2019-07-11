<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function path()
    {
        return "/events/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    /**
     * Record a new Activity for an Event
     *
     * @param string $description
     * @return void
     */
    public function recordActivity($description)
    {
        $this->activity()->create(compact('description'));
    }
}
