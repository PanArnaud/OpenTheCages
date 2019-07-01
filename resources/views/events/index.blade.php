@extends('layouts.app')

@section('content')
  <header class="flex items-center mb-3 py-4">
    <div class="flex justify-between items-end w-full">
      <h2 class="text-grey text-sm font-normal">My Events</h2>
      <a href="/events/create" class="button">Add Event</a>
    </div>
  </header>

  <main class="lg:flex lg:flex-wrap -mx-3">
    @forelse ($events as $event)
      <div class="lg:w-1/3 px-3 pb-6">
        <div class="card" style="height: 200px;">
          <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-light pl-4">
            <a href="{{ $event->path() }}" class="text-black no-underline">{{ $event->title }}</a>
          </h3>
          
          <div class="text-grey">{{ str_limit($event->description, 100) }}</div>
        </div>
      </div>
    @empty
      <div>No events yet.</div>  
    @endforelse
    </main>
@endsection