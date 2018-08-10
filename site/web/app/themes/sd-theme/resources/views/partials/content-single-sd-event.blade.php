<article @php post_class() @endphp>
  <header>
    <figure>
      {{-- Display the featured image --}}
      @include('partials/image-header')
    </figure>
    <div>
      <p>{{ SingleSdEvent::start_time() }} - {{ SingleSdEvent::end_time() }}</p>
      <p>{{ SingleSdEvent::date() }}</p>
      <p>{{ SingleSdEvent::venue() }}</p>
    </div>
    <div>
      <a href="{{ SingleSdEvent::facebook_url() }}">
        See event on facebook
      </a>
    </div>
    <h1 class="entry-title">{{ get_the_title() }}</h1>
  </header>
  <div class="entry-content">
    {{ get_the_content() }}
  </div>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
