{{-- Creates grid of cards --}}
<div class="container-fluid posts-grid">
  <div class="row justify-content-center">
    @if (!have_posts())
      @include('plugin::components.warning')
    @else
      @while (have_posts()) @php the_post() @endphp
        @include('plugin::partials.card', ['post' => $grid_config['wrapper'](get_post())])
      @endwhile
    @endif
  </div>
</div>
