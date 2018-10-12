{{-- Creates grid of cards --}}
<div class="container-fluid posts-grid">
  <div class="row">
    @foreach ($posts as $post)
      <div class="col-md-{{ 12 / $column_count }}">
      @php
      $post = $post_wrapper_factory(get_post());
      @endphp
        <div class="card">
          {{-- Insert component content --}}
          @php
          if (!isset($single_post_template))
          {
            $single_post_template = 'articles::partials.single-post';
          }
          @endphp
          @include($single_post_template, ['post' => $post])
          {{-- Supply link for card --}}
          <a class="card-link" href={{ $post->url() }}></a>
        </div>
      </div>
    @endforeach
  </div>
</div>
