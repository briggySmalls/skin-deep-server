@php $product = new SkinDeep\Shop\Product($post); @endphp
<figure class="product-figure" style="background-color: {{ $product->backgroundColour() }}">
  @if ($product->hasImage())
  <div class="product-image">
    @include(
      'partials.components.image-header',
      [
        'post' => $product,
        'image_sizes' => ArchiveSdProduct::archiveSizes($post)
      ])
    @endif
  </div>
</figure>
<div class="card-body">
  <h5 class="card-title">{!! $product->title() !!}</h5>
  <div class="card-text">
    @if ($product->inStock())
      £{{ $product->price() }}
    @else
      Out of stock
    @endif
  </div>
</div>
