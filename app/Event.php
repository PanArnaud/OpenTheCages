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
}
