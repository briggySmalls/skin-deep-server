{{-- Creates grid of cards --}}
<div class="container-fluid posts-grid">
  <div class="row justify-content-center">
    @if (!have_posts())
      @include('articles::components.warning')
    @else
      @while (have_posts()) @php the_post() @endphp
        <div class="col-md-{{ 12 / $column_count }}">
        @php
        $post = $grid_config['wrapper'](get_post());
        @endphp
          <div @php post_class('card', $post->ID) @endphp>
            {{-- Insert card content --}}
            @include($grid_config['template']($post->ID))
            {{-- Supply link for card --}}
            <a class="card-link" href={{ $post->url() }}></a>
          </div>
        </div>
      @endwhile
    @endif
  </div>
</div>
