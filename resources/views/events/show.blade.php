@extends('layouts.app')

@section('content')
  <h1>{{$event->title }}</h1>
  <div>{{ $event->description }}</div>
  <a href="/events">Go Back</a>
@endsection