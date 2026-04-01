@php
  // Build section classes
  $section_classes = 'hero-section';

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
  @php
    $resolve_media = function ( $media ): ?array {
        if ( is_array( $media ) && ! empty( $media['url'] ) ) {
            return [
                'url'    => $media['url'],
                'alt'    => $media['alt']   ?? '',
                'width'  => $media['width'] ?? null,
                'height' => $media['height'] ?? null,
            ];
        }
        if ( is_string( $media ) && $media !== '' ) {
            return [ 'url' => $media, 'alt' => '', 'width' => null, 'height' => null ];
        }
        return null;
    };

    $desktop_type  = $content->desktop_media_type ?? 'image';
    $mobile_type   = $content->mobile_media_type  ?? 'image';

    $desktop_image = $resolve_media( $content->desktop_background_image ?? null );
    $desktop_video = $resolve_media( $content->desktop_background_video ?? null );
    $mobile_image  = $resolve_media( $content->mobile_background_image  ?? null );
    $mobile_video  = $resolve_media( $content->mobile_background_video  ?? null );
  @endphp

  <div class="hero-bg">

    {{-- ===== DESKTOP ===== --}}
    @if ( $desktop_type === 'video' && $desktop_video )

      <video
        src="{{ esc_url( $desktop_video['url'] ) }}"
        @if ( $desktop_video['width']  ) width="{{ $desktop_video['width'] }}"   @endif
        @if ( $desktop_video['height'] ) height="{{ $desktop_video['height'] }}" @endif
        class="h-full w-full object-cover max-767:hidden!"
        autoplay
        playsinline
        loop
        muted
        preload="metadata"
      ></video>

    @elseif ( $desktop_type === 'image' && $desktop_image )

      <img
        src="{{ esc_url( $desktop_image['url'] ) }}"
        alt="{{ esc_attr( $desktop_image['alt'] ?: 'Hero background' ) }}"
        @if ( $desktop_image['width']  ) width="{{ $desktop_image['width'] }}"   @endif
        @if ( $desktop_image['height'] ) height="{{ $desktop_image['height'] }}" @endif
        class="h-full w-full object-cover destop-img max-767:hidden!"
        loading="eager"
      >

    @endif

    {{-- ===== MOBILE ===== --}}
    @if ( $mobile_type === 'video' && $mobile_video )

      <video
        src="{{ esc_url( $mobile_video['url'] ) }}"
        @if ( $mobile_video['width']  ) width="{{ $mobile_video['width'] }}"   @endif
        @if ( $mobile_video['height'] ) height="{{ $mobile_video['height'] }}" @endif
        class="hidden h-full w-full object-cover max-767:block"
        autoplay
        playsinline
        loop
        muted
        preload="metadata"
      ></video>

    @elseif ( $mobile_type === 'image' && $mobile_image )

      <img
        src="{{ esc_url( $mobile_image['url'] ) }}"
        alt="{{ esc_attr( $mobile_image['alt'] ?: 'Hero background' ) }}"
        @if ( $mobile_image['width']  ) width="{{ $mobile_image['width'] }}"   @endif
        @if ( $mobile_image['height'] ) height="{{ $mobile_image['height'] }}" @endif
        class="hidden h-full w-full object-cover max-767:block"
        loading="eager"
      >

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

        {{-- Banner Heading --}}
        @if ( ! empty( $content->banner_heading ) )
          <div class="title title-white">
            <h1>{!! wp_kses_post( $content->banner_heading ) ? $content->banner_heading : '' !!}</h1>
          </div>
        @endif

        {{-- Short Description --}}
        @if ( ! empty( $content->banner_short_description ) && is_scalar( $content->banner_short_description ) )
          <div class="content white mt-20">
            {!! wp_kses_post( $content->banner_short_description )  !!}
          </div>
        @endif


        {{-- CTA Buttons --}}
        @php
          $book_link = $content->book_your_flight_button ?? null;
          $view_link = $content->view_more_button ?? null;
        @endphp

        @if ( $book_link || $view_link )
          <div class="hero-btn-group">

            {{-- Book Your Flight --}}
            @if ( is_array($book_link) && !empty($book_link['url']) )
              <a
                href="{{ esc_url($book_link['url']) }}"
                class="btn btn-orange"
                aria-label="{{ esc_attr($book_link['title'] ?? 'Book Your Flight') }}"
                @if(!empty($book_link['target'])) target="{{ esc_attr($book_link['target']) }}" @endif
              >
                {{ esc_html($book_link['title'] ?? 'Book Your Flight') }}
              </a>
            @endif


            {{-- View Tours --}}
            @if ( is_array($view_link) && !empty($view_link['url']) )
              <a
                href="{{ esc_url($view_link['url']) }}"
                class="btn btn-blue"
                aria-label="{{ esc_attr($view_link['title'] ?? 'View Tours') }}"
                @if(!empty($view_link['target'])) target="{{ esc_attr($view_link['target']) }}" @endif
              >
                {{ esc_html($view_link['title'] ?? 'View Tours') }}
              </a>
            @endif

          </div>
        @endif

        {{-- Stats Counter --}}
        @if ( ! empty( $content->home_counter_repeater ) && is_iterable( $content->home_counter_repeater ) )
          <div class="hero-stats">
            @foreach ( $content->home_counter_repeater as $_row )
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

      @php
        $hasContent = !empty($content->fo_title)
          || !empty($content->fo_price)
          || !empty($content->fo_price_suffix)
          || !empty($content->fo_features_list);
        $pricingAriaLabel = ($hasContent && !empty($content->fo_title) && !empty($content->fo_price))
          ? 'aria-label="' . esc_attr($content->fo_title) . ' $' . esc_attr($content->fo_price) . '"'
          : '';
      @endphp

      @if($hasContent)
        <div class="hero-content-right" {!! $pricingAriaLabel !!}>
          <div class="hero-price-box">

            @if(!empty($content->fo_title))
              <div class="flex items-center justify-between">
                <h5>{{ $content->fo_title }}</h5>
                <button type="button" class="hero-price-close" aria-label="Close pricing callout">
                  &times;
                </button>
              </div>
            @endif

            @if(!empty($content->fo_price))
              <div class="hero-price-head">
                <h2 class="h1">${{ $content->fo_price }}</h2>
                @if(!empty($content->fo_price_suffix))
                  <small>{{ $content->fo_price_suffix }}</small>
                @endif
              </div>
            @endif

            @if(!empty($content->fo_features_list))
              <ul class="hero-price-features" aria-label="Pricing highlights">
                @foreach($content->fo_features_list as $item)
                  @if(!empty($item['feature_text']))
                    <li>{{ $item['feature_text'] }}</li>
                  @endif
                @endforeach
              </ul>
            @endif

          </div>
        </div>
      @endif

    </div>

  </div>

</section>
