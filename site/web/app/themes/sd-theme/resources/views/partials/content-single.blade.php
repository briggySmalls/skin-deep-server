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
    <h1 class="entry-title">{{ the_title() }}</h1>
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
  @php $magazine = SinglePost::magazine(); @endphp
  @if ($magazine)
    <div class="jumbotron">
      <div class="container">
        <div class="row">
          <div class="col-sm-auto">
            <h1 class="display-4">Get the full magazine</h1>
            <p class="lead">This piece is from our print edition: {{ $magazine->post_title }}</p>
            <a class="buy-button" href="{{ get_permalink($magazine->ID) }}">Buy it now</a>
          </div>
          <div class="col-sm">
            {{-- TODO: Make an article wrapper like for Product --}}
            {!! get_the_post_thumbnail($magazine->ID, 'thumbnail') !!}
          </div>
        </div>
      </div>
    </div>
  @endif
  <div class="entry-content">
    {{ the_content() }}
  </div>
  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
