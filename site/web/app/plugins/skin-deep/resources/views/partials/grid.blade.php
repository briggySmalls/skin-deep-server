{{-- Creates grid of cards --}}
<div class="container-fluid posts-grid">
  <div class="row justify-content-center">
    @if (!$posts)
      @include('plugin::components.warning')
    @else
      @foreach ($posts as $raw_post)
        @include('plugin::partials.card', ['post' => $grid_config['wrapper']($raw_post)])
      @endforeach
    @endif
  </div>
</div>
