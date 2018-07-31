{{-- Feature video takes precedence --}}
@if (get_field( 'sd_featured_video', $article->ID ))
  <div class="embed-responsive embed-responsive-16by9">
    {!! get_field('sd_featured_video', $article->ID) !!}
  </div>
{{-- Otherwise display the featured image --}}
@elseif (has_post_thumbnail( $article->ID ) )
  {!! get_the_post_thumbnail($article->ID, $image_size, isset($image_classes) ? ['class' => $image_classes] : []) !!}
@endif
