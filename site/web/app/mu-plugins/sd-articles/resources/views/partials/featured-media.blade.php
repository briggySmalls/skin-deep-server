@if ($post->hasImage())
  {{-- Display the featured image --}}
  {!! get_the_post_thumbnail(
    $post->ID,
    $image_size,
    isset($image_classes) ? ['class' => $image_classes] : []) !!}
@endif
