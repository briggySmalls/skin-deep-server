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
      data-item-id="{{ SingleSdProduct::id() }}"
      data-item-name="{{ SingleSdProduct::name() }}"
      data-item-price="{{ SingleSdProduct::price() }}"
      data-item-url="{{ SingleSdProduct::url() }}"
      data-item-description="{{ SingleSdProduct::description()}}">
          Buy
    </button>
  </div>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
