{{-- Display featured image/video --}}
@include(
  'partials.components.featured-media',
  ['image_sizes' => \SkinDeep\Articles\PostsPreview::sizes($column_count)])
<div class="card-body">
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
          @if ($post->venueUrl())
            <a href="{{ $post->venueUrl() }}">
          @endif
          {{ $post->venue() }}</a>
          @if ($post->venueUrl())
            </a>
          @endif
      @endif
    </div>
  </div>
  {{-- Display title --}}
  <h3 class="card-title">{!! $post->title() !!}</h3>
  {{-- Display excerpt --}}
  <div class="card-text">
    {!! get_the_excerpt($post->ID) !!}
  </div>
</div>
