@if ($post->hasImage())
  <div class="featured-image postition-relative">
    {{-- Display the featured image --}}
    {!! $post->image([
      'classes' => $image_classes ?? false,
      'sizes' => $image_sizes ?? false,
      'extended' => true,
    ]) !!}
  </div>
@endif
