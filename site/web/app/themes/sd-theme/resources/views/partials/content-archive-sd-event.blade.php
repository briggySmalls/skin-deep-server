<div class="card">
  <div class="row no-gutters">
    <div class="col-md-4">
      {{-- Display featured image/video --}}
      {{-- Feature video takes precedence --}}
      @if (SinglePost::has_featured_video())
        <div class="embed-responsive embed-responsive-16by9">
          {!! SinglePost::video() !!}
        </div>
      {{-- Otherwise display the featured image --}}
      @elseif (SinglePost::has_featured_image())
        {!! SinglePost::image() !!}
      @endif
    </div>
    <div class="col">
      {{-- Display category --}}
      <h5>
        @foreach (get_the_category(get_post()) as $category)
          {{ $category->name }}
        @endforeach
      </h5>
      {{-- Display title --}}
      <h4 class="card-title">{{ get_post()->post_title }}</h4>
      {{-- Display excerpt --}}
      @php $details = $sd_events_api->getEventDetails(); @endphp
      <p class="card-text">{{ SingleSdEvent::toDatetimeString($details) }}</p>
    </div>
  </div>
</div>
