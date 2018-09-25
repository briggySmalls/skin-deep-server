@if ($post->hasImage())
  <div class="featured-image postition-relative">
    @if ($post->hasVideo())
      <i class="fas fa-video video-icon" title="Video article"></i>
    @endif
    {{-- Display the featured image --}}
    {!! get_the_post_thumbnail(
      $post->ID,
      $image_size,
      isset($image_classes) ? ['class' => $image_classes] : []) !!}
  </div>
@endif
