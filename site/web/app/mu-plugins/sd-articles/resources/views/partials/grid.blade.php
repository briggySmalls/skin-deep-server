{{-- Creates grid of cards --}}
<div class="container-fluid posts-grid">
  <div class="row justify-content-center">
    @foreach ($posts as $raw_post)
      <div class="col-md-{{ 12 / $column_count }}">
      @php
      $post = $post_wrapper_factory($raw_post);
      @endphp
        <div @php post_class('card', $post->ID) @endphp>
          {{-- Insert card content --}}
          @include($card_template)
          {{-- Supply link for card --}}
          <a class="card-link" href={{ $post->url() }}></a>
        </div>
      </div>
    @endforeach
  </div>
</div>
