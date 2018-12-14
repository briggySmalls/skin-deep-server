<div class="col-md-{{ 12 / $grid_config['column_count']  }}">
  <div @php post_class('card', $post->ID) @endphp>
    {{-- Insert card content --}}
    @include($grid_config['template']($post->ID))
    {{-- Supply link for card --}}
    <a class="card-link" href={{ $post->url() }}></a>
  </div>
</div>
