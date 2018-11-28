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
        <hr>
        <div class="product-price">
          £{{ $post->price() }}
        </div>
        <hr>
      </header>
      <section class="entry-content">
        {{ the_content() }}
      </section>
      <section class="product-details">
        @include('partials.components.buy-button')
      </section>
    </div>
  </div>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
