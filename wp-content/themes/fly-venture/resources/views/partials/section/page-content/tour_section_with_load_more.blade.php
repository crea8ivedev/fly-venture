@php
  // ── Section classes ──────────────────────────────────────────────
  $section_classes = 'tempa-tour-wrap border-0 border-t-lightblue border-t-2 pt-80 pb-100 max-1023:py-40';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }
  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';

  // ── Background color ─────────────────────────────────────────────
  $bg_color = ( ! empty( $content->background_color ) && is_scalar( $content->background_color ) )
      ? $content->background_color
      : '#f5f9fc';

  // ── Section icon ─────────────────────────────────────────────────
  $icon_url = '';
  if ( ! empty( $content->ompt_icon ) ) {
      $icon_id  = is_array( $content->ompt_icon )
          ? absint( $content->ompt_icon['ID'] ?? 0 )
          : absint( $content->ompt_icon );
      $icon_url = $icon_id ? wp_get_attachment_image_url( $icon_id, 'full' ) : '';
  }

  // ── Selected category ────────────────────────────────────────────
  $tour_category = ( ! empty( $content->select_tour_category->slug ) && is_scalar( $content->select_tour_category->slug ) )
      ? sanitize_key( $content->select_tour_category->slug )
      : '';

  // ── Load more text ───────────────────────────────────────────────
  $load_more_text = ( ! empty( $content->load_more_button_text ) && is_scalar( $content->load_more_button_text ) )
      ? $content->load_more_button_text
      : 'Load More';

  // ── Asset URIs ───────────────────────────────────────────────────
  $star_uri        = get_theme_file_uri( '/resources/images/star.svg' );
  $unfill_star_uri = get_theme_file_uri( '/resources/images/unfill-star.svg' );
  $clock_uri       = get_theme_file_uri( '/resources/images/blue-clock.svg' );
  $best_price_uri  = get_theme_file_uri( '/resources/images/best-price.svg' );

  // ── Initial query ────────────────────────────────────────────────
  $per_page = 6;

  $query_args = [
      'post_type'              => 'tours',
      'post_status'            => 'publish',
      'posts_per_page'         => $per_page,
      'no_found_rows'          => false,
      'ignore_sticky_posts'    => true,
      'update_post_meta_cache' => true,
      'update_post_term_cache' => true,
  ];

  if ( ! empty( $tour_category ) ) {
      $query_args['tax_query'] = [
          [
              'taxonomy' => 'tour_category',
              'field'    => 'slug',
              'terms'    => $tour_category,
          ],
      ];
  }

  $initial_query = new WP_Query( $query_args );

  $total_tours = $initial_query->found_posts;
  $has_more    = $total_tours > $per_page;
@endphp

<section
  class="{{ $section_classes }}"
  style="background-color: {{ esc_attr( $bg_color ) }};"
  data-ajax-url="{{ esc_url( admin_url('admin-ajax.php') ) }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif
>
  <div class="container-fluid">

    {{-- ── Section Header ───────────────────────────────────────── --}}
    <div class="common-head fadeText">

      @if ( ! empty( $icon_url ) )
        <div class="common-head-icon" aria-hidden="true">
          <img src="{{ esc_url( $icon_url ) }}" width="68" height="100" alt="section icon">
        </div>
      @endif

      @if ( ! empty( $content->ompt_title ) && is_scalar( $content->ompt_title ) )
        <div class="title title-blue">
          <h2>{{ $content->ompt_title }}</h2>
        </div>
      @endif

      @if ( ! empty( $content->ompt_description ) )
        <div class="content content-black">
          {!! wp_kses_post( $content->ompt_description ) !!}
        </div>
      @endif

    </div>

    {{-- ── Tours Grid ────────────────────────────────────────────── --}}
    <div
      id="tampa-tours-grid"
      class="tempa-tours-grid grid grid-cols-3 max-1199:grid-cols-2 max-992:grid-cols-1 gap-45 max-1441:gap-30 items-center"
    >

      @forelse ( $initial_query->posts as $_tour )
        @php


          $terms    = get_the_terms( $_tour->ID, 'tour_category' );
          $tourTags = get_the_terms( $_tour->ID, 'tour_tag' );

          $citySlug = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->slug : '';
          $cityName = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';

          $thumbnail   = get_the_post_thumbnail_url( $_tour->ID, 'medium_large' ) ?: '';
          $priceBlock  = function_exists( 'get_field' ) ? get_field( 'price_block',       $_tour->ID ) : [];
          $flightDur   = function_exists( 'get_field' ) ? (array) get_field( 'flight_duration', $_tour->ID ) : [];
          $bestFor     = function_exists( 'get_field' ) ? (string) get_field( 'best_for',       $_tour->ID ) : '';
          $buttonGroup = function_exists( 'get_field' ) ? (array) get_field( 'button_group',    $_tour->ID ) : [];
          $reviewBlock = function_exists( 'get_field' ) ? (array) get_field( 'review_block',    $_tour->ID ) : [];
//dd($buttonGroup);
          $priceBlock    = is_array( $priceBlock ) ? $priceBlock : [];
          $originalPrice = $priceBlock['regular_price'] ?? '';
          $offerPrice    = $priceBlock['offer_price']    ?? '';
          $perPersonText    = $priceBlock['per_person_text']    ?? '';

          $durationLabel = $flightDur['flight_duration_text'] ?? 'Flight duration';
          $duration      = $flightDur['flight_duration']      ?? '';
          $bookUrl       = ! empty( $buttonGroup['book_now_button']['url'] )   ? $buttonGroup['book_now_button']['url']   : 'javascript:void(0);';
          $bookButtonText       = ! empty( $buttonGroup['book_now_button']['title'] )   ? $buttonGroup['book_now_button']['title']   : 'BOOK NOW';

       $learnButtonText = !empty($buttonGroup['learn_more_button'])
    ? (is_array($buttonGroup['learn_more_button'])
        ? implode(', ', $buttonGroup['learn_more_button'])
        : $buttonGroup['learn_more_button'])
    : '';

          $rating = floatval($reviewBlock['select_star_rating'] ?? 4.5);
          $reviewText    = $reviewBlock['review_text'] ?? '';

          $badgeLabel = '';
          $tour_icon  = '';
          if ( ! is_wp_error( $tourTags ) && ! empty( $tourTags ) ) {
              $badgeLabel = $tourTags[0]->name;
                $tour_icon  = get_field('tour_tag_icon', 'term_' . $tourTags[0]->term_id);

          }
          $priceTags = [];
          if ( ! empty( $priceBlock['select_price_tag'] ) ) {
              $selectedPriceTags = $priceBlock['select_price_tag'];

              if ( $selectedPriceTags instanceof WP_Term ) {
                  $selectedPriceTags = [ $selectedPriceTags ];
              }

              if ( is_array( $selectedPriceTags ) ) {
                  foreach ( $selectedPriceTags as $term ) {
                      if ( ! ( $term instanceof WP_Term ) ) {
                          continue;
                      }
                      $termId      = $term->term_id;
                      $tptIconId   = get_term_meta( $termId, 'tpt_icon',         true );
                      $tptColor    = get_term_meta( $termId, 'tpt_color',        true );
                      $textColor   = get_term_meta( $termId, 'tpt_text_color',   true );
                      $tooltipText = get_term_meta( $termId, 'tpt_tooltip_text', true );

                      $priceTags[] = [
                          'name'         => $term->name,
                          'slug'         => $term->slug,
                          'color'        => ! empty( $tptColor )  ? sanitize_hex_color( $tptColor )  : '',
                          'text_color'   => ! empty( $textColor ) ? sanitize_hex_color( $textColor ) : '',
                          'tooltip_text' => ! empty( $tooltipText ) ? $tooltipText : '',
                          'icon_url'     => ! empty( $tptIconId )
                              ? ( filter_var( $tptIconId, FILTER_VALIDATE_URL )
                                  ? esc_url_raw( $tptIconId )
                                  : wp_get_attachment_image_url( absint( $tptIconId ), 'full' ) )
                              : '',
                      ];
                  }
              }
          }
        @endphp
        <div class="popular-tour-card" data-city="{{ esc_attr( $citySlug ) }}">
          <div class="popular-tour-card-media">
            @if ( ! empty( $thumbnail ) )
              <img src="{{ esc_url( $thumbnail ) }}" height="250" width="375"
                   alt="{{ esc_attr( $_tour->post_title ) }}">
            @endif
            @if ( ! empty( $badgeLabel ) )
              <div class="popular-tour-badge">
                  <?php if (!empty($tour_icon['url'])) : ?>
                <img
                  src="<?php echo esc_url($tour_icon['url']); ?>"
                  height="14"
                  width="14"
                  alt="<?php echo esc_attr($tour_icon['alt']); ?>">
                <?php endif; ?>
                <span>{{ $badgeLabel }}</span>
              </div>
            @endif
            @if ( ! empty( $cityName ) )
              <span class="popular-tour-pill">{{ $cityName }}</span>
            @endif
          </div>

          <div class="popular-tour-card-body">
            <div class="top-content">
              <h4>{{ $_tour->post_title }}</h4>

              <div class="popular-tour-meta">
                <div>
                  @if ( ! empty( $originalPrice ) )
                    <small>From <span>{{ esc_html( $originalPrice ) }}</span></small>
                  @endif
                  <div class="flex items-baseline gap-2">
                  @if ( ! empty( $offerPrice ) )
                    <strong>Now {{ esc_html( $offerPrice ) }}</strong>
                  @endif
                    @if ( ! empty( $perPersonText ) )
                    <div class="normal-tag">
                      <span>{{esc_html($perPersonText)}}</span>
                    </div>
                    @endif
                  </div>
                  @if ( ! empty( $priceTags ) )
                    @foreach ( $priceTags as $tag )
                      <div class="best-price-tag price-tag-item"
                           style="{{ ! empty( $tag['color'] ) ? 'background-color:' . esc_attr( $tag['color'] ) . ';' : '' }}">

                        @if ( ! empty( $tag['icon_url'] ) )
                          <img src="{{ esc_url( $tag['icon_url'] ) }}" height="14" width="14"
                               alt="{{ esc_attr( $tag['name'] ) }}">
                        @endif

                        <span style="color:{{ esc_attr( $tag['text_color'] ) }};">
                            {{ $tag['name'] }}
                           </span>

                        @if ( ! empty( $tag['tooltip_text'] ) )
                        <div class="price-tag-tooltip-wrap">
                          <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                               xmlns="http://www.w3.org/2000/svg">
                            <path
                              d="M5.3565 7.3565C5.45217 7.2605 5.5 7.14167 5.5 7V5C5.5 4.85833 5.452 4.73967 5.356 4.644C5.26 4.54833 5.14133 4.50033 5 4.5C4.85867 4.49967 4.74 4.54767 4.644 4.644C4.548 4.74033 4.5 4.859 4.5 5V7C4.5 7.14167 4.548 7.2605 4.644 7.3565C4.74 7.4525 4.85867 7.50033 5 7.5C5.14133 7.49967 5.26017 7.45217 5.3565 7.3565ZM5.3565 3.356C5.45217 3.26033 5.5 3.14167 5.5 3C5.5 2.85833 5.452 2.73967 5.356 2.644C5.26 2.54833 5.14133 2.50033 5 2.5C4.85867 2.49967 4.74 2.54767 4.644 2.644C4.548 2.74033 4.5 2.859 4.5 3C4.5 3.141 4.548 3.25983 4.644 3.3565C4.74 3.45317 4.85867 3.501 5 3.5C5.14133 3.499 5.26017 3.451 5.3565 3.356ZM5 10C4.30833 10 3.65833 9.86867 3.05 9.606C2.44167 9.34333 1.9125 8.98717 1.4625 8.5375C1.0125 8.08783 0.656334 7.55867 0.394001 6.95C0.131667 6.34133 0.000333966 5.69133 6.32911e-07 5C-0.0003327 4.30867 0.131001 3.65867 0.394001 3.05C0.657001 2.44133 1.01317 1.91217 1.4625 1.4625C1.91183 1.01283 2.441 0.656667 3.05 0.394C3.659 0.131333 4.309 0 5 0C5.691 0 6.341 0.131333 6.95 0.394C7.559 0.656667 8.08817 1.01283 8.5375 1.4625C8.98683 1.91217 9.34317 2.44133 9.6065 3.05C9.86983 3.65867 10.001 4.30867 10 5C9.999 5.69133 9.86767 6.34133 9.606 6.95C9.34433 7.55867 8.98817 8.08783 8.5375 8.5375C8.08683 8.98717 7.55767 9.3435 6.95 9.6065C6.34233 9.8695 5.69233 10.0007 5 10ZM5 9C6.11667 9 7.0625 8.6125 7.8375 7.8375C8.6125 7.0625 9 6.11667 9 5C9 3.88333 8.6125 2.9375 7.8375 2.1625C7.0625 1.3875 6.11667 1 5 1C3.88333 1 2.9375 1.3875 2.1625 2.1625C1.3875 2.9375 1 3.88333 1 5C1 6.11667 1.3875 7.0625 2.1625 7.8375C2.9375 8.6125 3.88333 9 5 9Z"
                              fill="{{ esc_attr( $tag['text_color'] ) }}"/>
                          </svg>
                          <div class="price-tag-tooltip">
                            {{ $tag['tooltip_text'] }}
                          </div>
                        </div>
                        @endif


                      </div>
                    @endforeach
                  @endif
                </div>

                <div>
                  <small><strong>{{ esc_html( $durationLabel ) }}</strong></small>
                  @if ( ! empty( $duration ) )
                    <div class="time mt-6 inline-flex gap-6 items-center">
                      <img src="{{ esc_url( $clock_uri ) }}" height="18" width="18" alt="clock">
                      <strong>{{ esc_html( $duration ) }} min</strong>
                    </div>
                  @endif
                </div>
              </div>

              @if ( ! empty( $_tour->post_content ) )
                <p>{!! wp_kses_post( $_tour->post_content ) !!}</p>
              @endif

              @if ( ! empty( $bestFor ) )
                <p><strong>Best for:</strong> {{ esc_html( $bestFor ) }}</p>
              @endif
            </div>

            <div class="bottom-content">
              <div class="popular-tour-btns">

                <a href="{{ esc_url( $bookUrl ) }}" class="btn btn-orange" aria-label="Book now" role="link">{{ $bookButtonText }}</a>
                <a href="{{ esc_url(get_permalink( $_tour->ID )) }}" class="btn btn-b-white" aria-label="Learn more" role="link">
                  {!! $learnButtonText !!} </a>
              </div>

              <div class="popular-tour-rating">
                  <?php echo flyventure_render_svg_rating($rating, $_tour->ID); ?>
                  <?php echo wp_strip_all_tags($reviewText); ?>
              </div>
            </div>
          </div>
        </div>

      @empty
        <p class="col-span-3 text-center">No tours found.</p>
      @endforelse

    </div>{{-- /#tampa-tours-grid --}}

    @php wp_reset_postdata(); @endphp

    {{-- ── Load More Button ─────────────────────────────────────── --}}
    @if ( $has_more )
      <div id="tampa-load-more-wrap" class="btn-custom mt-66 max-1023:mt-24 flex justify-center">
        <button
          id="tampa-load-more"
          class="btn btn-orange popular-tour-cta"
          data-nonce="{{ wp_create_nonce( 'flyventure_load_more_tours' ) }}"
          data-offset="{{ $per_page }}"
          data-per-page="{{ $per_page }}"
          data-total="{{ $total_tours }}"
          data-load-more-text="{{ esc_attr( $load_more_text ) }}"
          data-category="{{ esc_attr( $tour_category ) }}"
        >
          {{ $load_more_text }}
        </button>
      </div>
    @endif

  </div>
</section>
