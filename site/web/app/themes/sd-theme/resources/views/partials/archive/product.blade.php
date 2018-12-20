@php $product = new SkinDeep\Shop\Product($post); @endphp
<figure class="product-figure" style="background-color: {{ $product->backgroundColour() }}">
  @if ($product->hasImage())
  <div class="product-image">
    @include(
      'partials.components.image-header',
      [
        'post' => $product,
        'image_sizes' => ArchiveSdProduct::archiveSizes($post, $grid_config['column_count'])
      ])
    @endif
  </div>
</figure>
<div class="card-body d-xl-flex">
  <h5 class="card-title mr-auto">{!! $product->title() !!}</h5>
</div>
<div class="card-footer d-flex">
  @include('partials.components.buy-button', ['classes' => 'ml-auto'])
</div>
