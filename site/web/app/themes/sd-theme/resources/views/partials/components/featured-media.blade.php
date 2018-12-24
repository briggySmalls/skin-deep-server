@php
$image = $post->image();
@endphp
@if ($image)
  <div class="featured-image postition-relative">
    {{-- Display the featured image --}}
    {!! $image->html([
      'classes' => $image_classes ?? null,
      'sizes' => $image_sizes ?? null,
      'extended' => $extended ?? null,
    ]) !!}
  </div>
@endif
