{{-- First display the featured media --}}
<figure>
  @if ($post->hasVideo())
    {{-- Feature video takes precedence --}}
    @include('partials.components.video-header')
  @elseif ($post->hasImage())
    {{-- Otherwise display the featured image --}}
    @include('partials.components.image-header')
  @endif
</figure>
{{-- Now display the main article --}}
<article @php post_class('container') @endphp>
  @include('partials.single.'.get_post_type())
</article>
