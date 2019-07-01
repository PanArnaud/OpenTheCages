<div class="card" style="height: 200px;">
  <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-light pl-4">
    <a href="{{ $event->path() }}" class="text-black no-underline">{{ $event->title }}</a>
  </h3>
  
  <div class="text-grey">{{ str_limit($event->description, 100) }}</div>
</div>