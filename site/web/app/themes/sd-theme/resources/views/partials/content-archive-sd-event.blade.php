<div class="card">
  <div class="row card-content">
    <div class="col-md-4">
      {{-- Display the featured image --}}
      @if ($post->hasImage())
        @include(
          'partials.components.image-header',
          [
            'post' => $post,
            'image_sizes' => \SkinDeep\Articles\PostsPreview::sizes(3)
          ])
      @endif
    </div>
    <div class="col content">
      {{-- Display title --}}
      <h4 class="card-title">{!! $post->title() !!}</h4>
      <div class="event-details">
        <div class="event-detail-item">
          @if (SingleSdEvent::getDisplayTime($post))
            {{-- Display time --}}
            <i class="far fa-clock event-icon" title="Time"></i>
            {{ SingleSdEvent::getDisplayTime($post) }}
          @endif
        </div>
        <div class="event-detail-item">
          @if (SingleSdEvent::getDisplayDate($post))
            <i class="far fa-calendar event-icon" title="Date"></i>
            {{ SingleSdEvent::getDisplayDate($post) }}
          @endif
        </div>
        <div class="event-detail-item">
          @if ($post->venue())
              <i class="fas fa-map-marker-alt event-icon" title="Venue"></i>
              {{ $post->venue() }}
          @endif
        </div>
      </div>
      <p>{!! the_excerpt() !!}</p>
    </div>
  </div>
  {{-- Supply link for card --}}
  <a class="card-link" href={{ $post->url() }}></a>
</div>
