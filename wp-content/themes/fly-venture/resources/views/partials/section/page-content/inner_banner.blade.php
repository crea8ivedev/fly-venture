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

    {{-- Desktop Image --}}
    @if ( ! empty( $content->background_image ) )
      @if ( is_array( $content->background_image ) && ! empty( $content->background_image['url'] ) )
        <img
          src="{{ esc_url( $content->background_image['url'] ) }}"
          alt="{{ esc_attr( $content->background_image['alt'] ?? 'Hero background' ) }}"
          @if ( ! empty( $content->background_image['width'] ) )  width="{{ absint( $content->background_image['width'] ) }}"  @endif
          @if ( ! empty( $content->background_image['height'] ) ) height="{{ absint( $content->background_image['height'] ) }}" @endif
          class="h-full w-full object-cover desktop-img max-767:hidden!"
          loading="eager"
        >
      @elseif ( is_string( $content->background_image ) && $content->background_image !== '' )
        <img
          src="{{ esc_url( $content->background_image ) }}"
          alt="Hero background"
          class="h-full w-full object-cover desktop-img max-767:hidden!"
          loading="eager"
        >
      @endif
    @endif

    {{-- Mobile Video --}}
    @if ( ! empty( $content->mobile_video ) )
      @php
        $_video     = $content->mobile_video;
        $_video_url = is_array( $_video )
            ? ( $_video['url'] ?? '' )
            : ( is_string( $_video ) ? $_video : '' );
      @endphp
      @if ( ! empty( $_video_url ) )
        <video
          src="{{ esc_url( $_video_url ) }}"
          preload="none"
          autoplay
          playsinline
          loop
          muted
          class="hidden h-full w-full object-cover max-767:block"
          @if ( is_array( $_video ) && ! empty( $_video['width'] ) )  width="{{ absint( $_video['width'] ) }}"  @endif
          @if ( is_array( $_video ) && ! empty( $_video['height'] ) ) height="{{ absint( $_video['height'] ) }}" @endif
        ></video>
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
