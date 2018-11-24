@php
assert(is_a($post, 'SkinDeep\Shop\Product'));
@endphp
<article @php post_class('container') @endphp>
  <div class="row">
    <div class="col-md">
      <figure>
        {{-- Display the featured image --}}
        @include('partials.components.image-header')
      </figure>
    </div>
    <div class="col-md">
      <h1 class="entry-title">{!! $post->title() !!}</h1>
      <div class="entry-content">
        {{ the_content() }}
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
              Buy
            @else
              Out of stock
            @endif
        </button>
      </div>
    </div>
  </div>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
