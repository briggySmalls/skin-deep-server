<article @php post_class() @endphp>
  <header>
    <figure>
      {{-- Display the featured image --}}
      @include('partials/image-header')
    </figure>
    <h1 class="entry-title">{{ get_the_title() }}</h1>
  </header>
  <div class="entry-content">
    {{ get_the_content() }}
  </div>
  <div>
    <button
      class="snipcart-add-item"
      data-item-id="{{ get_the_ID() }}"
      data-item-name="{{ get_the_title() }}"
      data-item-price="{{ get_field('sd-product-price') }}"
      data-item-url="{{ get_permalink() }}"
      data-item-description="{{ get_field('sd-product-description') }}">
          Buy
    </button>
  </div>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
