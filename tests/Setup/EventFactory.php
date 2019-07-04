<?php

namespace Tests\Setup;

use App\Task;
use App\User;
use App\Event;

class EventFactory
{
	protected $user;
	protected $tasksCount = 0;

	public function create()
	{
		$event = factory(Event::class)->create([
			'owner_id' => $this->user ?? factory(User::class)
		]);

		factory(Task::class, $this->tasksCount)->create([
			'event_id' => $event->id			
		]);

		return $event;
	}

	public function ownedBy(User $user)
	{
		$this->user = $user;

		return $this;
	}

	public function withTasks($count)
	{
		$this->tasksCount = $count;

		return $this;
	}
}