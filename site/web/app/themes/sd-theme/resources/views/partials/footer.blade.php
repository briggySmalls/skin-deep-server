<footer id="footer" class="content-info">
  <nav class="navbar navbar-light bg-light navbar-expand-lg">
    {{-- Footer navigation --}}
    @if (has_nav_menu('footer_navigation'))
      {!! wp_nav_menu(['theme_location' => 'footer_navigation']) !!}
    @endif
  </nav>
  <div class="container">
    @php dynamic_sidebar('sidebar-footer') @endphp
  </div>
</footer>
