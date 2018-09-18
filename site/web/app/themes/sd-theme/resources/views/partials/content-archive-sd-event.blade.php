<div class="card">
  <div class="row no-gutters">
    <div class="col-md-4">
      {{-- Display the featured image --}}
      @if ($event->hasImage())
        {!! $event->image() !!}
      @endif
    </div>
    <div class="col content">
      {{-- Display title --}}
      <h4 class="card-title">{{ $event->post_title }}</h4>
      {{-- Display time --}}
      @if ($event->start_time)
        <p class="card-text">{{ SingleSdEvent::getDisplayTime($event) }}</p>
      @endif
      <p>{!! the_excerpt() !!}</p>
    </div>
  </div>
</div>
