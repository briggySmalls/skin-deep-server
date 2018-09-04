<div class="card">
  {{-- Display featured image/video --}}
  {{-- Feature video takes precedence --}}
  @if (SinglePost::hasFeaturedVideo())
    <div class="embed-responsive embed-responsive-16by9">
      {!! SinglePost::video() !!}
    </div>
  {{-- Otherwise display the featured image --}}
  @elseif (SinglePost::hasFeaturedImage())
    {!! SinglePost::image() !!}
  @endif
  <div class="card-body">
    {{-- Display category --}}
    <h5>
      @foreach (get_the_category(get_post()) as $category)
        {{ $category->name }}
      @endforeach
    </h5>
    {{-- Display title --}}
    <h4 class="card-title">{{ get_post()->post_title }}</h4>
    {{-- Display excerpt --}}
  </div>
</div>
