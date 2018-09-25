 <article @php post_class() @endphp>
  <header>
    <figure>
      {{-- Display the featured image --}}
      @include('partials/image-header', ['post' => $event])
    </figure>
    <h1 class="entry-title">{{ $event->title() }}</h1>
    <ul id="event-details">
      @if ($event->startTime())
        <li>
          <i class="fas fa-clock event-icon fa-fw" title="Time"></i>
          {{ SingleSdEvent::getDisplayTime($event) }}
        </li>
      @endif
      @if ($event->venue())
        <li>
          <i class="fas fa-map-marker-alt event-icon fa-fw" title="Venue"></i>
          {{ $event->venue() }}
        </li>
      @endif
      @if ($event->facebookUrl())
        <li>
          <i class="fab fa-facebook-f event-icon fa-fw" title="Facebook"></i>
          <a href="{{ $event->facebookUrl() }}">See event on facebook</a>
        </li>
      @endif
    </ul>
  </header>
  <div class="entry-content">
    {{ the_content() }}
  </div>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
