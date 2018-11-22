{{--
  Widget output template
  --}}
{{-- Display the category title --}}
<div class="preview-posts">
  <h2 class="title">
    <a href={{ $url }}>
      {{ $title }}
    </a>
  </h2>
  {{-- Create grid of posts --}}
  @include("articles::partials.grid", ['posts' => $posts])
  {{-- Display see more link --}}
  <div class="see-more"><a href="{{ $url }}">See more articles</a></div>
</div>
