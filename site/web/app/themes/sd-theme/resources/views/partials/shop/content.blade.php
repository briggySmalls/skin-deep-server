{{-- Display products grouped by category --}}
<div class="accordion shop" id="categoryContainer">
  @foreach($categories as $category)
  <div class="card category">
    <div class="card-header" id="{{ $category->slug }}-heading">
      <h5 class="mb-0">
        <button class="btn"
                type="button"
                data-toggle="collapse"
                data-target="#{{ $category->slug }}"
                aria-expanded="true"
                aria-controls="{{ $category->slug }}">
          {{ $category->name }} @if($category->description) {{ " - " . $category->description }} @endif
        </button>
      </h5>
    </div>
    <div id="{{ $category->slug }}"
         class="{{ $loop->last ? "collapse show" : "collapse" }}"
         aria-labelledby="{{ $category->slug }}"
         data-parent="#categoryContainer">
      @include(
        'articles::partials.grid',
        [
          'single_post_template' => 'partials/shop/product',
          'posts' => $products[$category->term_id],
          'column_count' => $column_count,
        ])
    </div>
  </div>
  @endforeach
</div>
