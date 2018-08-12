<article @php post_class() @endphp>
  <header>
    <figure>
      {{-- Display the featured image --}}
      @include('partials/image-header')
    </figure>
    <h1 class="entry-title">{{ get_the_title() }}</h1>
    <ul id="event-details">
      <li><i class="fas fa-clock event-icon"></i>{{ SingleSdEvent::start_time() }}-{{ SingleSdEvent::end_time() }}</li>
      <li><i class="fas fa-calendar-alt event-icon"></i>{{ SingleSdEvent::date() }}</li>
      <li><i class="fas fa-map-marker-alt event-icon"></i>{{ SingleSdEvent::venue() }}</li>
    </ul>
    <div>
      <a href="{{ SingleSdEvent::facebook_url() }}">
        See event on facebook
      </a>
    </div>
  </header>
  <div class="entry-content">
    {{ get_the_content() }}
  </div>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
