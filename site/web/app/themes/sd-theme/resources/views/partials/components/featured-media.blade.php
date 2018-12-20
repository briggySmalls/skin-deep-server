@if ($post->hasImage())
  <div class="featured-image postition-relative">
    {{-- Display the featured image --}}
    {!! $post->image([
      'classes' => $image_classes ?? null,
      'sizes' => $image_sizes ?? null,
      'extended' => $extended ?? null,
    ]) !!}
  </div>
@endif
