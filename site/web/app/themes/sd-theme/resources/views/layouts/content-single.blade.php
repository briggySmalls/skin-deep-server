{{-- First display the featured media --}}
<figure class="featured-image">
  @if ($post->hasVideo())
    {{-- Feature video takes precedence --}}
    @include('partials.components.video-header')
  @elseif ($post->image())
    {{-- Otherwise display the featured image --}}
    @include('partials.components.image-header', ['extended' => true, 'show_artists' => true])
  @endif
</figure>
{{-- Now display the main article --}}
<article @php post_class('container') @endphp>
  <header class="single-header">
    @yield('single-header')
  </header>
  <div class="entry-content">
    @yield('single-content')
  </div>
</article>
@yield('after-content')
@php comments_template('/partials/comments.blade.php') @endphp
