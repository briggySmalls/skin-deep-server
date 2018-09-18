{{-- Creates grid of cards --}}
<div class="container-fluid posts-grid">
  <div class="row">
    @foreach ($posts as $post)
      <div class="col-md-{{ 12 / $column_count }}">
        {{-- Wrap the entire card in a link --}}
        <a href={{ $post->url() }}>
          <div class="card">
            {{-- Insert component content --}}
            @php
            if (!isset($single_post_template))
            {
              $single_post_template = 'articles::partials.single_post';
            }
            @endphp
            @include($single_post_template, ['post' => $post])
          </div>
        </a>
      </div>
    @endforeach
  </div>
</div>
