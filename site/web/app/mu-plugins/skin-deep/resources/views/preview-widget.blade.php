{{--
  Widget output template
  --}}
{{-- Display the category title --}}
<div class="preview-posts">
  <h2 class="title">
    {{ $title }}
  </h2>
  {{-- Create grid of posts --}}
  @include('articles::partials.grid')
  {{-- Display see more link --}}
  <div class="see-more"><a href="{{ $url }}">See more</a></div>
</div>
