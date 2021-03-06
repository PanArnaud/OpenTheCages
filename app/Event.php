<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use RecordsActivity;

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

    public function members()
    {
        return $this->belongsToMany(User::class, 'event_members');
    }

    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }
}
