{{-- Display featured image/video --}}
@include(
  'partials.components.featured-media',
  ['image_sizes' => \SkinDeep\Articles\PostsPreview::sizes($column_count)])
<div class="card-body">
  @include('partials.components.event-details')
  {{-- Display title --}}
  <h3 class="card-title">{!! $post->title() !!}</h3>
  {{-- Display excerpt --}}
  <div class="card-text">
    {!! get_the_excerpt($post->ID) !!}
  </div>
</div>
