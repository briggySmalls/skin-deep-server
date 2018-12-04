<header id="main-header" class="banner">
      {{-- Don't bother with a link, it's supplied already --}}
  <nav class="navbar top-level">
    <div class="navigation-grid">
      <div class="logo">
        @if (has_custom_logo())
          @php the_custom_logo() @endphp
        @else
          <a class="navbar-brand" href="{{ home_url('/') }}">
            {{ get_bloginfo('name', 'display') }}
          </a>
        @endif
      </div>
      <div class="top-nav">
        {{-- Main menu navigation --}}
        @if (has_nav_menu('primary_navigation'))
          {{-- Standard 'top-level' menu items --}}
          {!! wp_nav_menu([
            'theme_location' => 'primary_navigation',
            'depth' => 1,
            'menu_class' => 'header-menu']) !!}
        @endif
      </div>
      <div class="sub-nav">
        {{-- Add sub-menu with custom walker for children --}}
        @if (has_nav_menu('primary_navigation'))
          {!! wp_nav_menu([
            'theme_location' => 'primary_navigation',
            'walker' => new SkinDeep\Theme\ChildOnlyNavWalker(),
            'depth' => 0,
            'menu_class' => 'header-menu']) !!}
        @endif
      </div>
      <div class="search">
        {{-- Search bar --}}
        {!! get_search_form() !!}
      </div>
    </div>
  </nav>
</header>
