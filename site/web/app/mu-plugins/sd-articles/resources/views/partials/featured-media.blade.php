@if ($post->hasImage())
  <div class="featured-image postition-relative">
    {{-- Display the featured image --}}
    {!! $post->image($image_classes ?? false, $image_sizes ?? false) !!}
  </div>
@endif
