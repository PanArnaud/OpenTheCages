<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
  <h1>OpenTheCages</h1>

  <ul>
    @forelse ($events as $event)
      <li>
        <a href="{{ $event->path() }}">{{ $event->title }}</a>
      </li>
    @empty
      <li>No events yet.</li>  
    @endforelse
  </ul>
</body>
</html>