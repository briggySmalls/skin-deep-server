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
      {{ $post->venue() }}
    </span>
  @endif
  @if ($post->facebookUrl() && isset($show_facebook) && $show_facebook)
    <span>
      <a href="{{ $post->facebookUrl() }}">Facebook event</a>
    </span>
  @endif
</div>
