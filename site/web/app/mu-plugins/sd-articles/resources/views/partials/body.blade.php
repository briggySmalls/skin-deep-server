{{-- Display category --}}
<h4>
  @foreach (get_the_category($article) as $category)
    {{ $category->name }}
  @endforeach
</h4>
{{-- Display title --}}
<h4 class="card-title">{{ $article->post_title }}</h4>
{{-- Display excerpt --}}
