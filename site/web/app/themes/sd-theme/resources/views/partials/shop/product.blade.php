@if (has_post_thumbnail($post->ID))
  {!! get_the_post_thumbnail($post->ID, 'post_thumbnail'); !!}
@endif
@php $product = new App\SkinDeep\Product($post); @endphp
<div class="card-body">
  <h5 class="card-title">{{ $product->title() }}</h5>
  {{-- TODO: Make function to display price (inc. denomination) --}}
</div>
<div class="card-footer">
  <p class="card-text">
    @if ($product->in_stock())
      £{{ $product->price() }}
    @else
      Out of stock
    @endif
  </p>
</div>
