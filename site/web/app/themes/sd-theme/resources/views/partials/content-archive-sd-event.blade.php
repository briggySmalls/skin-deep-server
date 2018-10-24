<div class="card">
  <div class="row no-gutters card-content">
    <div class="col-md-4">
      {{-- Display the featured image --}}
      @if ($event->hasImage())
        @include(
          'partials.components.image-header',
          [
            'post' => $event,
            'image_sizes' => \SkinDeep\Articles\PostsPreview::sizes(3)
          ])
      @endif
    </div>
    <div class="col content">
      {{-- Display title --}}
      <h4 class="card-title">{!! $event->title() !!}</h4>
      {{-- Display time --}}
      @if ($event->startTime())
        <p class="card-text">{{ SingleSdEvent::getDisplayTime($event) }}</p>
      @endif
      <p>{!! the_excerpt() !!}</p>
    </div>
  </div>
  {{-- Supply link for card --}}
  <a class="card-link" href={{ $event->url() }}></a>
</div>
