@php
assert(is_a($post, 'SkinDeep\Shop\Product'));
@endphp
<article @php post_class('container') @endphp>
  <div class="row">
    <div class="col-md">
      <figure class="featured-image">
        {{-- Display the featured image --}}
        @include('partials.components.image-header')
      </figure>
    </div>
    <div class="col-md">
      <header class="single-header">
        <h1 class="entry-title">{!! $post->title() !!}</h1>
        @include('partials.components.socials')
      </header>
      <div class="entry-content">
        {{ the_content() }}
      </div>
      <hr/>
      <div class="product-details d-flex flex-row-reverse justify-content-end align-items-center">
        @include('partials.components.buy-button')
      </div>
    </div>
  </div>
  {{-- Output related posts in a special location --}}
  @if (class_exists('Jetpack_RelatedPosts'))
    <hr/>
    {!! do_shortcode('[jetpack-related-posts]') !!}
  @endif
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
