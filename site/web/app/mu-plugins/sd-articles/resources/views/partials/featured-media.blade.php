@if ($post->video())
  {{-- Feature video takes precedence --}}
  <div class="embed-responsive embed-responsive-16by9">
    {!! $post->video() !!}
  </div>
@elseif ($post->hasImage())
  {{-- Otherwise display the featured image --}}
  {!! get_the_post_thumbnail($post->ID, $image_size, isset($image_classes) ? ['class' => $image_classes] : []) !!}
@endif
