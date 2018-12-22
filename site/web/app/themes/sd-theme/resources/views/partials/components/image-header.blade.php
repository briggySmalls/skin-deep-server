@php
$image = $post->image();
@endphp
{!! $image->html([
  'classes' => $image_classes ?? null,
  'sizes' => $image_sizes ?? null,
  'extended' => $extended ?? null,
]) !!}
<figcaption>

</figcaption>
