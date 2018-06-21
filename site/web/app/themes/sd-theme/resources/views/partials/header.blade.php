<header class="banner">
  <div class="container">
    <a class="brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>
    <nav class="nav-primary">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
      @endif
    </nav>
    <div class="snipcart-summary">
      <a href="#" class="snipcart-user-profile">
        <span class="snipcart-user-email">Login</span>
      </a>
      <a href="#" class="snipcart-user-logout">
        Logout
      </a>
    </div>
  </div>
</header>
