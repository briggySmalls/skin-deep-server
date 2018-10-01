@php $product = new SkinDeep\Shop\Product($post); @endphp
@if ($product->hasImage())
  @include('partials/image-header', ['post' => $product])
@endif
<div class="card-body">
  <h5 class="card-title">{{ $product->title() }}</h5>
</div>
<div class="card-footer">
  <p class="card-text">
    @if ($product->inStock())
      £{{ $product->price() }}
    @else
      Out of stock
    @endif
  </p>
</div>
