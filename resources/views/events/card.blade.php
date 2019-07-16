<div class="card" style="height: 200px;">
  <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-light pl-4">
    <a href="{{ $event->path() }}" class="text-black no-underline">{{ $event->title }}</a>
  </h3>
  
  <div class="text-grey mb-4">{{ str_limit($event->description, 100) }}</div>

  <footer>
    <form method="POST" action="{{ $event->path() }}" class="text-right">
      @method("DELETE")
      @csrf
      <button class="text-xs" type="submit">Delete</button>
    </form>
  </footer>
</div>