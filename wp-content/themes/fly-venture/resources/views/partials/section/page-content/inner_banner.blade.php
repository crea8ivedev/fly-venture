@php
  // Build section classes
  $section_classes = 'hero-section inner-hero-section';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }

  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }

  // Resolve section ID
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';

  // Hoist star URI outside loop
  $star_uri = get_theme_file_uri( '/resources/images/star.svg' );
@endphp

<section
  class="{{ $section_classes }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif
>

  {{-- Background --}}
  <div class="hero-bg">

    {{-- Desktop: Image or Video --}}
    @if ( ( $content->background_type_desktop ?? '' ) === 'video' )
      @php
        $_d_video     = $content->background_video_desktop ?? null;
        $_d_video_url = is_array( $_d_video ) ? ( $_d_video['url'] ?? '' ) : ( is_string( $_d_video ) ? $_d_video : '' );
      @endphp
      @if ( ! empty( $_d_video_url ) )
        <video
          src="{{ esc_url( $_d_video_url ) }}"
          preload="none"
          autoplay
          playsinline
          loop
          muted
          class="h-full w-full object-cover desktop-img max-767:hidden!"
        ></video>
      @endif
    @else
      @php $_d_img = $content->background_image_desktop ?? null; @endphp
      @if ( is_array( $_d_img ) && ! empty( $_d_img['url'] ) )
        <img
          src="{{ esc_url( $_d_img['url'] ) }}"
          alt="{{ esc_attr( $_d_img['alt'] ?? 'Hero background' ) }}"
          @if ( ! empty( $_d_img['width'] ) )  width="{{ absint( $_d_img['width'] ) }}"  @endif
          @if ( ! empty( $_d_img['height'] ) ) height="{{ absint( $_d_img['height'] ) }}" @endif
          class="h-full w-full object-cover desktop-img max-767:hidden!"
          loading="eager"
        >
      @elseif ( is_string( $_d_img ) && $_d_img !== '' )
        <img
          src="{{ esc_url( $_d_img ) }}"
          alt="Hero background"
          class="h-full w-full object-cover desktop-img max-767:hidden!"
          loading="eager"
        >
      @endif
    @endif

    {{-- Mobile: Image or Video (falls back to desktop if mobile fields are empty) --}}
    @php
      $mobile_has_video = ! empty( $content->background_video_mobile ) && ( is_array( $content->background_video_mobile ) ? ! empty( $content->background_video_mobile['url'] ) : true );
      $mobile_has_image = ! empty( $content->background_image_mobile ) && ( is_array( $content->background_image_mobile ) ? ! empty( $content->background_image_mobile['url'] ) : true );
      $mobile_type      = ( $mobile_has_video || $mobile_has_image )
          ? ( $content->background_type_mobile ?? '' )
          : ( $content->background_type_desktop ?? '' );
      $mobile_video_src = ( $mobile_has_video || $mobile_has_image ) ? ( $content->background_video_mobile ?? null ) : ( $content->background_video_desktop ?? null );
      $mobile_image_src = ( $mobile_has_video || $mobile_has_image ) ? ( $content->background_image_mobile  ?? null ) : ( $content->background_image_desktop ?? null );
    @endphp
    @if ( $mobile_type === 'video' )
      @php
        $_m_video_url = is_array( $mobile_video_src ) ? ( $mobile_video_src['url'] ?? '' ) : ( is_string( $mobile_video_src ) ? $mobile_video_src : '' );
      @endphp
      @if ( ! empty( $_m_video_url ) )
        <video
          src="{{ esc_url( $_m_video_url ) }}"
          preload="none"
          autoplay
          playsinline
          loop
          muted
          class="hidden h-full w-full object-cover max-767:block"
        ></video>
      @endif
    @else
      @if ( is_array( $mobile_image_src ) && ! empty( $mobile_image_src['url'] ) )
        <img
          src="{{ esc_url( $mobile_image_src['url'] ) }}"
          alt="{{ esc_attr( $mobile_image_src['alt'] ?? 'Hero background' ) }}"
          @if ( ! empty( $mobile_image_src['width'] ) )  width="{{ absint( $mobile_image_src['width'] ) }}"  @endif
          @if ( ! empty( $mobile_image_src['height'] ) ) height="{{ absint( $mobile_image_src['height'] ) }}" @endif
          class="hidden h-full w-full object-cover max-767:block"
          loading="eager"
        >
      @elseif ( is_string( $mobile_image_src ) && $mobile_image_src !== '' )
        <img
          src="{{ esc_url( $mobile_image_src ) }}"
          alt="Hero background"
          class="hidden h-full w-full object-cover max-767:block"
          loading="eager"
        >
      @endif
    @endif

  </div>

  <div class="hero-overlay"></div>

  <div class="container-fluid h-full">
    <div class="hero-content-wrap">
      <div class="hero-content-left fadeText">

        {{-- Rating --}}
        @if ( ! empty( $content->select_rating ) || ! empty( $content->rating_text ) )
          <div class="hero-rating">

            @if ( ! empty( $content->select_rating ) )
              @php
                $rating_value = is_array( $content->select_rating )
                    ? absint( $content->select_rating[0] ?? 5 )
                    : absint( $content->select_rating );
                $rating_value = max( 1, min( 5, $rating_value ) );
              @endphp
              <ul class="hero-stars">
                @for ( $s = 0; $s < $rating_value; $s++ )
                  <li>
                    <img
                      src="{{ esc_url( $star_uri ) }}"
                      height="16"
                      width="17"
                      alt="star"
                    >
                  </li>
                @endfor
              </ul>
            @endif

            @if ( ! empty( $content->rating_text ) )
              <p>{{ is_scalar( $content->rating_text ) ? $content->rating_text : '' }}</p>
            @endif

          </div>
        @endif

        {{-- Inner Banner Heading --}}
        {{-- ✅ Uses inner_banner_heading key (differs from home banner's banner_heading) --}}
        @if ( ! empty( $content->inner_banner_heading ) )
          <div class="title title-white">
            <h1>{{ is_scalar( $content->inner_banner_heading ) ? $content->inner_banner_heading : '' }}</h1>
          </div>
        @endif

        {{-- Short Description --}}
        {{-- ✅ Uses inner_banner_short_description key --}}
        @if ( ! empty( $content->inner_banner_short_description ) && is_scalar( $content->inner_banner_short_description ) )
          <div class="content white mt-20">
            <p>{!! nl2br( esc_html( $content->inner_banner_short_description ) ) !!}</p>
          </div>
        @endif

        {{-- Stats Counter --}}
        {{-- ✅ Uses inner_counter_repeater key (differs from home banner's home_counter_repeater) --}}
        @if ( ! empty( $content->inner_counter_repeater ) && is_iterable( $content->inner_counter_repeater ) )
          <div class="hero-stats">
            @foreach ( $content->inner_counter_repeater as $_row )
              <div class="hero-stat-item">
                @if ( ! empty( $_row['number'] ) )
                  <h3>{{ is_scalar( $_row['number'] ) ? $_row['number'] : '' }}</h3>
                @endif
                @if ( ! empty( $_row['text'] ) )
                  <p>{{ is_scalar( $_row['text'] ) ? $_row['text'] : '' }}</p>
                @endif
              </div>
            @endforeach
          </div>
        @endif

      </div>
    </div>
  </div>

</section>
