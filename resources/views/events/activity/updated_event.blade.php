@if (count($activity->changes['after']) == 1)
  {{ $activity->username() }} updated {{ key($activity->changes['after']) }} of the event
@else
  {{ $activity->username() }} updated the event
@endif