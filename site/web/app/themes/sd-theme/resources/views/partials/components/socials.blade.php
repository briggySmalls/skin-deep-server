@if (function_exists('sharing_display'))
  {!! sharing_display('', true) !!}
@endif

@if (class_exists('Jetpack_Likes'))
  @php $custom_likes = new Jetpack_Likes; @endphp
  {!! $custom_likes->post_likes('') !!}
@endif
