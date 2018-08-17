<article @php post_class() @endphp>
  <header>
    <figure>
      {{-- Display the featured image --}}
      @include('partials/image-header')
    </figure>
    <h1 class="entry-title">{{ get_the_title() }}</h1>
    <ul id="event-details">
      <li><i class="fas fa-clock event-icon"></i>{{ SingleSdEvent::toDatetimeString($details) }}</li>
      <li><i class="fas fa-map-marker-alt event-icon"></i>{{ $details->venue }}</li>
    </ul>
    <div>
      @if ($details->facebook_url)
        <a href="{{ $details->facebook_url }}">
          See event on facebook
        </a>
      @endif
    </div>
  </header>
  <div class="entry-content">
    {{ get_the_content() }}
  </div>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
