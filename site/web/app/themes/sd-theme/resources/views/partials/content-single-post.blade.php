<article @php post_class() @endphp>
  <header>
    <figure>
      @if ($article->hasVideo())
        {{-- Feature video takes precedence --}}
        @include('partials/video-header')
      @elseif ($article->hasImage())
        {{-- Otherwise display the featured image --}}
        @include('partials/image-header', ['post' => $article])
      @endif
    </figure>
    @include('partials/entry-meta')
    <h1 class="entry-title">{{ $article->title() }}</h1>
    {{-- Display author(s) --}}
    @if (count($article->authors()))
      <p class="byline author vcard">
        {{ __('Written by', 'sage') }}
        @foreach ($article->authors() as $author)
          <a href="{{ get_term_link($author) }}" rel="author">{{ $author->name }}</a>{{ !$loop->last ? ", " : "" }}
        @endforeach
      </p>
    @endif
  </header>
  @php $magazine = $article->magazine(); @endphp
  @if ($magazine)
    <div class="jumbotron">
      <div class="container">
        <div class="row">
          <div class="col-sm-auto">
            <h1 class="display-4">Get the full magazine</h1>
            <p class="lead">This piece is from our print edition: {{ $magazine->title() }}</p>
            <a class="buy-button" href="{{ $magazine->url() }}">Buy it now</a>
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
  @php dynamic_sidebar('sidebar-post') @endphp
  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
