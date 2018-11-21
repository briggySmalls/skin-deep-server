@php
assert(is_a($post, 'SkinDeep\Events\Event'));
@endphp
<header>
  <h1 class="entry-title">{!! $post->title() !!}</h1>
  <ul class="event-details">
    @if ($post->startTime())
      <li>
        <i class="far fa-clock event-icon fa-fw" title="Time"></i>
        {{ SingleSdEvent::getDisplayTime($post) }}
      </li>
      <li>
        <i class="far fa-calendar event-icon fa-fw" title="Date"></i>
        {{ SingleSdEvent::getDisplayDate($post) }}
      </li>
    @endif
    @if ($post->venue())
      <li>
        <i class="fas fa-map-marker-alt event-icon fa-fw" title="Venue"></i>
        {{ $post->venue() }}
      </li>
    @endif
    @if ($post->facebookUrl())
      <li>
        <i class="fab fa-facebook-f event-icon fa-fw" title="Facebook"></i>
        <a href="{{ $post->facebookUrl() }}">See event on facebook</a>
      </li>
    @endif
  </ul>
</header>
<div class="entry-content">
  {{ the_content() }}
</div>
@php comments_template('/partials/comments.blade.php') @endphp
