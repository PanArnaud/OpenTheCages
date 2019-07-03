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
        @include('events.card')
      </div>
    @empty
      <div>No events yet.</div>  
    @endforelse
    </main>
@endsection