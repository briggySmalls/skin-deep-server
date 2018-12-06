{{-- Creates grid of cards --}}
<div class="container-fluid posts-grid">
  <div class="row justify-content-center">
    @if (!$posts)
      @include('plugin::components.warning')
    @else
      @foreach ($posts as $raw_post)
        <div class="col-md-{{ 12 / $column_count }}">
        @php
        $post = $grid_config['wrapper']($raw_post);
        @endphp
          <div @php post_class('card', $post->ID) @endphp>
            {{-- Insert card content --}}
            @include($grid_config['template']($post->ID))
            {{-- Supply link for card --}}
            <a class="card-link" href={{ $post->url() }}></a>
          </div>
        </div>
      @endforeach
    @endif
  </div>
</div>
