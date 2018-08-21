<article @php post_class() @endphp>
  <header>
    <figure>
      @if (SinglePost::hasFeaturedVideo())
      {{-- Feature video takes precedence --}}
      @include('partials/video-header')
      @elseif (SinglePost::hasFeaturedImage())
      {{-- Otherwise display the featured image --}}
      @include('partials/image-header')
      @endif
    </figure>
    @include('partials/entry-meta')
    <h1 class="entry-title">{{ get_the_title() }}</h1>
    {{-- Display author(s) --}}
    @if (count(SinglePost::authors()))
      <p class="byline author vcard">
        {{ __('Written by', 'sage') }}
        @foreach (SinglePost::authors() as $author)
          <a href="{{ get_term_link($author) }}" rel="author">{{ $author->name }}</a>{{ !$loop->last ? ", " : "" }}
        @endforeach
      </p>
    @endif
  </header>
  <div class="entry-content">
    {{ the_content() }}
  </div>
  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
