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
        <button class="snipcart-add-item buy-button"
        @if ($post->inStock())
          data-item-id="{{ $post->ID }}"
          data-item-name="{!! $post->title() !!}"
          data-item-price="{{ $post->price() }}"
          data-item-url="{{ $post->url() }}"
          data-item-description="{{ $post->description()}}"
        @else
          disabled
        @endif
          >
          @if ($post->inStock())
            Add to cart
          @else
            Out of stock
          @endif
        </button>
      </section>
    </div>
  </div>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
