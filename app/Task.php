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

        $this->event->recordActivity('completed_task');
    }
    
    public function incomplete()
    {
        $this->update([
            'completed' => false
        ]);

        $this->event->recordActivity('incompleted_task');
    }
}
