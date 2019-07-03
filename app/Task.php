<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $touches = ['event'];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function path()
    {
        return "{$this->event->path()}/tasks/{$this->id}";
    }
}
