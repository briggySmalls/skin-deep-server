{{-- Display featured image/video --}}
@include(
  'articles::partials.featured-media',
  ['image_size' => 'post-thumbnail'])
<div class="card-body">
  {{-- Display category --}}
  <p class="category-link">
    @foreach (get_the_category($post) as $category)
      @if (SkinDeep\Articles\Articles::is_default_category($category))
        @continue
      @endif
        {{ $category->name }}
    @endforeach
  </p>
  {{-- Display title --}}
  <h3 class="card-title">{{ $post->post_title }}</h3>
  {{-- TODO: Display excerpt? --}}
</div>
