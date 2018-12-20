<form role="search" method="get" class="site-search form-inline" action="{{ esc_url( home_url( '/' ) ) }}">
  <div class="input-group">
    <input
      type="search"
      class="form-control"
      placeholder="{!! esc_attr_x( 'Search&hellip;', 'placeholder' ) !!}"
      name="s"
      aria-label="Search" />
    <div class="input-group-append">
      <button class="search-btn" type="submit"><i title="Search" class="fas fa-search"></i></button>
    </div>
  </div>
</form>
