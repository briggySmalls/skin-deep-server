<article @php post_class() @endphp>
  <header>
    <figure>
      {{-- Display the featured image --}}
      @include('partials/image-header')
    </figure>
    <h1 class="entry-title">{{ get_the_title() }}</h1>
    <ul id="event-details">
      @if ($details->start_time)
        <li><i class="fas fa-clock event-icon"></i>{{ SingleSdEvent::getDisplayTime($details) }}</li>
      @endif
      @if ($details->venue)
        <li><i class="fas fa-map-marker-alt event-icon"></i>{{ $details->venue }}</li>
      @endif
      @if ($details->event_id)
        <li><i class="fab fa-facebook-f event-icon"></i><a href="{{ $details->facebookUrl() }}">See event on facebook</a></li>
      @endif
    </ul>
  </header>
  <div class="entry-content">
    {{ the_content() }}
  </div>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
