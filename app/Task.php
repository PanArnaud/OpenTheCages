<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $touches = ['event'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function path()
    {
        return "{$this->event->path()}/tasks/{$this->id}";
    }

    public function complete()
    {
        $this->update([
            'completed' => true
        ]);

        $this->recordActivity('completed_task');
    }
    
    public function incomplete()
    {
        $this->update([
            'completed' => false
        ]);

        $this->recordActivity('incompleted_task');
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    /**
     * Record a new Activity for an Event
     *
     * @param string $description
     * @return void
     */
    public function recordActivity($description)
    {
        $this->activity()->create([
            'event_id' => $this->event->id,
            'description' => $description,
        ]);
    }
}
