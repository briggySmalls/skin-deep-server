<div class="page-header">
  {{-- Title --}}
  <h1>{!! App::title() !!}</h1>
  {{-- Image --}}
  @php $image = App::image('large'); @endphp
  @if ($image)
    <figure>
      {!! $image !!}
    </figure>
  @endif
  {{-- Description --}}
  @php $description = App::description(); @endphp
  @if ($description)
    <figure>
      {!! $description !!}
    </figure>
  @endif
</div>
