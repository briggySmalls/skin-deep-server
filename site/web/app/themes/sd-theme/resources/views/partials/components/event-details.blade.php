<div class="event-details">
  @if ($post->startTime())
    <span>
      {{ SingleSdEvent::getDisplayTime($post) }}
    </span>
    <span>
      {{ SingleSdEvent::getDisplayDate($post) }}
    </span>
  @endif
  @if ($post->venue())
    <span>
      @if (isset($full_event_details))
        <a href="{{ $post->venueUrl() }}" target="_blank" rel="noopener noreferrer">
      @endif
      {{ $post->venue() }}
      @if (isset($full_event_details))
        </a>
      @endif
    </span>
  @endif
  @if ($post->facebookUrl() && isset($full_event_details))
    <span>
      <a href="{{ $post->facebookUrl() }}">Facebook event</a>
    </span>
  @endif
</div>
