@php
  // Build section classes
  $section_classes = 'testimonial-wrapper relative py-100 max-1023:py-40';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }

  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }

  // Resolve section ID
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';

  // Resolve review_button group fields
  $btn_group = ! empty( $content->review_button ) && is_array( $content->review_button )
      ? $content->review_button
      : [];

  // Fixed key: review_start_visibility (not review_start_visibility)
  $star_visibility = in_array( 'show', (array) ( $btn_group['review_star_visibility'] ?? [] ), true );

  // Star rating label e.g. "5.0"
  $btn_rating = isset( $btn_group['review_rating'] ) && is_scalar( $btn_group['review_rating'] )
      ? $btn_group['review_rating']
      : '';

  // ACF link array with url, title, target
  $btn_link = ! empty( $btn_group['review_button_link'] ) && is_array( $btn_group['review_button_link'] )
      ? $btn_group['review_button_link']
      : [];
@endphp

  <!-- testimonial-start -->
<section
  class="{{ $section_classes }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif
>
  <div class="title-container">
    <div class="container-fluid">
      <div class="flex flex-col justify-center items-center gap-20 text-center fadeText">

        {{-- Icon --}}
        @if ( ! empty( $content->rs_icon ) )
          @php( $_img = $content->rs_icon )
          <div class="icon">
            @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
              <img
                src="{{ esc_url( $_img['url'] ) }}"
                height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '100' }}"
                width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '100' }}"
                alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
                class="w-full h-full object-cover max-w-100 max-h-100 max-1199:max-w-50 max-1199:max-h-50"
                loading="lazy"
              >
            @elseif ( is_string( $_img ) && $_img !== '' )
              <img
                src="{{ esc_url( $_img ) }}"
                height="100"
                width="100"
                alt=""
                class="w-full h-full object-cover max-w-100 max-h-100 max-1199:max-w-50 max-1199:max-h-50"
                loading="lazy"
              >
            @endif
          </div>
        @endif

        {{-- Title --}}
        @if ( ! empty( $content->rs_title ) )
          <div class="title title-blue">
            <h2>{{ is_scalar( $content->rs_title ) ? $content->rs_title : '' }}</h2>
          </div>
        @endif

        {{-- Description --}}
        @if ( ! empty( $content->rs_desciption ) )
          <div class="content">
            {!! wp_kses_post( $content->rs_desciption ) !!}
          </div>
        @endif

        {{-- Review Button — only renders when visibility is "show" and URL is present --}}
        @if ( ! empty( $btn_link['url'] ) )
          <div class="btn-custom">
            <a
              href="{{ esc_url( $btn_link['url'] ) }}"
              class="btn-review"
              aria-label="{{ esc_attr( $btn_link['title'] ?? 'Reviews' ) }}"
              role="link"
              @if ( ! empty( $btn_link['target'] ) ) target="{{ esc_attr( $btn_link['target'] ) }}" @endif
            >
              @if($star_visibility)
              <svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.55696 13.6975L12.707 16.2075C13.467 16.6675 14.397 15.9875 14.197 15.1275L13.097 10.4075L16.767 7.2275C17.437 6.6475 17.077 5.5475 16.197 5.4775L11.367 5.0675L9.47696 0.6075C9.13696 -0.2025 7.97696 -0.2025 7.63696 0.6075L5.74696 5.0575L0.916957 5.4675C0.0369575 5.5375 -0.323043 6.6375 0.346957 7.2175L4.01696 10.3975L2.91696 15.1175C2.71696 15.9775 3.64696 16.6575 4.40696 16.1975L8.55696 13.6975Z" fill="url(#paint0_linear_2195_3507)"/>
                <defs>
                  <linearGradient id="paint0_linear_2195_3507" x1="0" y1="8.17791" x2="17.1139" y2="8.17791" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#F79808"/>
                    <stop offset="1" stop-color="#E26216"/>
                  </linearGradient>
                </defs>
              </svg>
              @endif
              @if ( ! empty( $btn_rating ) )
                <span>{{ $btn_rating }}</span>
              @endif

              {{ $btn_link['title'] ?? '' }}
            </a>
          </div>
        @endif

      </div>
    </div>
  </div>

</section>
