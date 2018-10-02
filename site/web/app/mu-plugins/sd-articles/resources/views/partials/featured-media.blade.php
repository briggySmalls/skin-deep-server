@if ($post->hasImage())
  <div class="featured-image postition-relative">
    @if ($post->hasVideo())
      <i class="fas fa-video video-icon" title="Video article"></i>
    @endif
    {{-- Display the featured image --}}
    {!! $post->image($image_classes ?? false, $image_sizes ?? false) !!}
  </div>
@endif
