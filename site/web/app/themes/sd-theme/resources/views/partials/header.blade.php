<header class="banner sticky-top">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navbarNavDropdown" class="collapse navbar-collapse">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'navbar-nav']) !!}
      @endif
      <div class="snipcart-summary">
        <a href="#" class="snipcart-user-profile nav-link">
          <span class="snipcart-user-email">Login</span>
        </a>
        <a href="#" class="snipcart-user-logout nav-link">
          Logout
        </a>
      </div>
      {!! get_search_form() !!}
    </div>
  </div>
</header>
