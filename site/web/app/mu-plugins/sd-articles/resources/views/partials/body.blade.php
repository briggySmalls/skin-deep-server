{{-- Display category --}}
<p class="category-link">
  @foreach (get_the_category($article) as $category)
    {{ $category->name }}
  @endforeach
</p>
{{-- Display title --}}
<h3 class="card-title">{{ $article->post_title }}</h3>
{{-- Display excerpt --}}
