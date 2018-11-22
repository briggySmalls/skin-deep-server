{{-- Creates grid of cards --}}
<div class="container-fluid posts-grid">
  <div class="row justify-content-center">
    @while (have_posts()) @php the_post() @endphp
      <div class="col-md-{{ 12 / $column_count }}">
      @php
      $post = $post_wrapper_factory(get_post());
      @endphp
        <div @php post_class('card', $post->ID) @endphp>
          {{-- Insert component content --}}
          @php
          if (!isset($single_post_template))
          {
            $single_post_template = 'articles::partials.single-post';
          }
          @endphp
          @include($single_post_template, ['article' => $post])
          {{-- Supply link for card --}}
          <a class="card-link" href={{ $post->url() }}></a>
        </div>
      </div>
    @endwhile
  </div>
</div>
