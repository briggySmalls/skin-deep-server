@php
$image = $post->image();
$artists = $image->artists();
@endphp
{!! $image->html([
  'classes' => $image_classes ?? null,
  'sizes' => $image_sizes ?? null,
  'extended' => $extended ?? null,
]) !!}
@if ($artists)
  <figcaption>
    Image by
    @foreach ($artists as $artist)
        {{ $artist->name }}@if (!$loop->last)<span>,&#32;</span>@endif
    @endforeach
    </span>
  </figcaption>
@endif
