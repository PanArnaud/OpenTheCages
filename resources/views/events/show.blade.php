@extends('layouts.app')

@section('content')
  <header class="flex items-center mb-3 py-4">
    <div class="flex justify-between items-center w-full">
      <p class="text-grey text-sm font-normal">
        <a href="/events" class="text-grey text-sm font-normal no-underline">My Events</a> / {{ $event->title }}
      </p>
      <a href="/events/create" class="button">Add Event</a>
    </div>
  </header>

  <main>
    <div class="lg:flex -mx-3">
      <div class="lg:w-3/4 px-3 mb-6">
          <div class="mb-8">
            <h2 class="text-grey mb-3 font-normal text-lg">Tasks</h2>
            
            @foreach ($event->tasks as $task)
              <div class="card mb-3">
                <form method="POST" action="{{ $task->path() }}">
                  @method('PATCH')
                  @csrf

                  <div class="flex items-center">
                    <input class="w-full {{ $task->completed ? 'text-grey' : '' }}" name="body" value="{{ $task->body }}">
                    <input name="completed" type="checkbox" {{ $task->completed ? 'checked' : '' }} onChange="this.form.submit()">
                  </div>
                </form>
              </div>
            @endforeach

              <div class="card mb-3">
                <form method="POST" action="{{ $event->path() . '/tasks' }}">
                  @csrf
                  <input class="w-full" placeholder="Add a new Task..." name="body">
                </form>
              </div>
          </div>
          
          <div>
            <h2 class="text-grey mb-3 font-normal text-lg">Notes</h2>
            <textarea class="card w-full" style="min-height: 200px;">Lorem Ipsum.</textarea>
          </div>
      </div>
      <div class="lg:w-1/4 px-3">
        @include('events.card')
      </div>
    </div>
  </main>

  
@endsection