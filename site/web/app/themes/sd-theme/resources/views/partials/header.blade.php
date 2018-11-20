<header id="main-header" class="banner">
  <nav class="navbar">
    @if (has_custom_logo())
      {{-- Don't bother with a link, it's supplied already --}}
      @php the_custom_logo() @endphp
    @else
      <a class="navbar-brand" href="{{ home_url('/') }}">
        {{ get_bloginfo('name', 'display') }}
      </a>
    @endif
    <button class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown"
            aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navbarNavDropdown" class="collapse navbar-collapse">
      <div class="flex-column align-items-start">
        {{-- Main menu navigation --}}
        @if (has_nav_menu('primary_navigation'))
          {{-- Standard 'top-level' menu items --}}
          {!! wp_nav_menu([
            'theme_location' => 'primary_navigation',
            'depth' => 1,
            'menu_class' => 'd-flex']) !!}
          {{-- Add sub-menu with custom walker for children --}}
          {!! wp_nav_menu([
            'theme_location' => 'primary_navigation',
            'walker' => new SkinDeep\Theme\ChildOnlyNavWalker(),
            'depth' => 0,
            'menu_class' => 'd-flex secondary-nav']) !!}
        @endif
      </div>
      {{-- Search bar --}}
      {!! get_search_form() !!}
    </div>
  </nav>
</header>
