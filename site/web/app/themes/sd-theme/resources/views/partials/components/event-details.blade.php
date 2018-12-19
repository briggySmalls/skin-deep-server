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
        {{-- Some notes on external links
          noopener: Protect against external links trying to navigate this page
          noreferrer: Don't 'refer' this site to the linked site
          nofollow: Stop google following this link as an 'endorsement'
          --}}
        <a href="{{ $post->venueUrl() }}" target="_blank" rel="noopener noreferrer nofollow">
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
