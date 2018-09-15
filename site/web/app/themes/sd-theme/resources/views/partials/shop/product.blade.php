@if (has_post_thumbnail($post->ID))
  {!! get_the_post_thumbnail($post->ID, 'post_thumbnail'); !!}
@endif
<div class="card-body">
  <h5 class="card-title">{{ $post->post_title }}</h5>
  {{-- TODO: Make function to display price (inc. denomination) --}}
  <p class="card-text">£{{ SingleSdProduct::price($post->ID) }}</p>
</div>
