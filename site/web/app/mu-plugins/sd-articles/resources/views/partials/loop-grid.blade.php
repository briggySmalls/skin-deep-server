{{-- Creates grid of cards --}}
<div class="container-fluid posts-grid">
  <div class="row">
    @while (have_posts()) @php the_post() @endphp
      @php
      $post = new \SkinDeep\Articles\Article(get_post())
      @endphp
      <div class="col-md-{{ 12 / $column_count }}">
        <div class="card">
          {{-- Insert component content --}}
          @php
          if (!isset($single_post_template))
          {
            $single_post_template = 'articles::partials.single_post';
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
