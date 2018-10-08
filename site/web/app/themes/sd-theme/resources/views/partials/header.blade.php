<header id="main-header" class="banner">
  <nav class="navbar navbar-light bg-light navbar-expand-lg">
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
        <div class="d-flex">
          {{-- Main menu navigation --}}
          @if (has_nav_menu('primary_navigation'))
            {!! wp_nav_menu(['theme_location' => 'primary_navigation']) !!}
          @endif
        </div>
        {{-- Display categories on an articles page --}}
        @if ($is_articles_page)
          <h2 class="d-lg-none">Categories</h2>
          <ul class="navbar-nav d-flex">
            @foreach (get_categories(['parent' => 0]) as $category)
              @if (\SkinDeep\Articles\Article::isDefaultCategory($category))
                @continue
              @endif
              <li class="nav-item">
                <a href="{{ get_category_link($category->term_id) }}" class="nav-link">
                  {{ $category->name }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
      {{-- Search bar --}}
      {!! get_search_form() !!}
    </div>
  </nav>
</header>
