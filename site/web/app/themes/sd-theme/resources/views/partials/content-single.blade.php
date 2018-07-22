<article @php post_class() @endphp>
  <header>
    <figure>
      @if (get_field( 'sd_featured_video', $post->ID ))
      {{-- Feature video takes precedence --}}
      @include('partials/video-header')
      @elseif (has_post_thumbnail( $post->ID ) )
      {{-- Otherwise display the featured image --}}
      @include('partials/image-header')
      @endif
    </figure>
    @include('partials/entry-meta')
    <h1 class="entry-title">{{ get_the_title() }}</h1>
    {{-- Display author(s) --}}
    @php $terms = wp_get_post_terms($post->ID, 'sd-author'); @endphp
    @if (count($terms))
      <p class="byline author vcard">
        {{ __('Written by', 'sage') }}
        @foreach ($terms as $term)
          <a href="{{ get_term_link($term) }}" rel="author">{{ $term->name }}</a>{{ !$loop->last ? ", " : "" }}
        @endforeach
      </p>
    @endif
  </header>
  <div class="entry-content">
    {{ get_the_content() }}
  </div>
  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
