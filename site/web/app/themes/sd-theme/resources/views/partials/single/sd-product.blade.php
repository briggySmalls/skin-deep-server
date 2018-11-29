@php
assert(is_a($post, 'SkinDeep\Shop\Product'));
@endphp
<article @php post_class('container') @endphp>
  <div class="row">
    <div class="col-md">
      <figure class="product-image">
        {{-- Display the featured image --}}
        @include('partials.components.image-header')
      </figure>
    </div>
    <div class="col-md">
      <header class="single-header">
        <h1 class="entry-title">{!! $post->title() !!}</h1>
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
  <hr/>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
