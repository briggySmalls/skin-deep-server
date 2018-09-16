<form role="search" method="get" class="site-search" action="{{ esc_url( home_url( '/' ) ) }}">
  <div class="input-group">
    <input
      type="search"
      class="form-control"
      placeholder="{!! esc_attr_x( 'Search&hellip;', 'placeholder' ) !!}"
      name="s"
      aria-label="Search" />
    <div class="input-group-append">
      <button type="button">Search</button>
    </div>
  </div>
</form>
