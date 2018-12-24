<!doctype html>
<html @php language_attributes() @endphp>
  @include('partials.head')
  <body @php body_class() @endphp>
    @php do_action('get_header') @endphp
    @include('partials.header')
    <div class="wrap container" role="document">
      <div id="content" class="content">
        <main class="main">
          @yield('content')
        </main>
        @if (SkinDeep\Theme\display_sidebar())
          <aside class="sidebar">
            @include('partials.sidebar')
          </aside>
        @endif
      </div>
    </div>
    @php do_action('get_footer') @endphp
    @include('partials.footer')
    @php wp_footer() @endphp
  {{-- Add custom content for the snipcart --}}
  <script id="cart-content-text" type="text/template">
    @include('partials.custom-snipcart')
  </script>
  </body>
</html>
