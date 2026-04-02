@extends('layouts.app')

@section('content')
  <section class="error-404-section py-200 max-1199:py-150 max-575:py-100">
    <div class="container-fluid text-center">
      <div class="flex flex-col justify-center items-center ">
        @if (!empty($error_image) && is_array($error_image))
          <div class="error-image">
            <img
              src="{{ esc_url($error_image['url']) }}"
              alt="{{ esc_attr($error_image['alt'] ?? '') }}"
              width="{{ esc_attr($error_image['width'] ?? '') }}"
              height="{{ esc_attr($error_image['height'] ?? '') }}"
            >
          </div>
        @endif

        @if (!empty($error_pre_heading))
          <h1 class="error-pre-heading">{!! wp_kses_post($error_pre_heading) !!}</h1>
        @endif

        @if (!empty($error_heading))
          <h4 class="error-heading tracking-normal! font-normal!">{!! wp_kses_post($error_heading) !!}</h4>
        @endif

      @php
        $go_home_button = (!empty($go_home_button) && is_array($go_home_button)) ? $go_home_button : [];
        $go_home_url    = esc_url($go_home_button['url'] ?? home_url('/'));
        $go_home_title  = esc_html($go_home_button['title'] ?? 'Go Home');
        $go_home_target = !empty($go_home_button['target']) ? esc_attr($go_home_button['target']) : '_self';
      @endphp
      </div>


      {{-- @if (!empty($go_home_title))
        <a href="{{ $go_home_url }}" target="{{ $go_home_target }}" class="btn btn-orange go-home-btn" aria-label="{{ $go_home_title }}" role="link">
          {{ $go_home_title }}
        </a>
      @endif --}}

    </div>
  </section>
@endsection
