<form role="search" method="get" class="search-form" action="{{ esc_url( home_url( '/' ) ) }}">
  <input type="search" class="search-field" placeholder="{!! esc_attr_x( 'Search&hellip;', 'placeholder' ) !!}" name="s" aria-label="Search" />
  <button class="search-submit">Search</button>
</form>
