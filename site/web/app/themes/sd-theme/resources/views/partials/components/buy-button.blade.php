<button class="snipcart-add-item buy-button {{ isset($classes) ? $classes : '' }}"
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
