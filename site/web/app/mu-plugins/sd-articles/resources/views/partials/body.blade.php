{{-- Display category --}}
<h5>
  @foreach (get_the_category($article) as $category)
    {{ $category->name }}
  @endforeach
</h5>
{{-- Display title --}}
<h4 class="card-title">{{ $article->post_title }}</h4>
{{-- Display excerpt --}}
