@php
    $hero = $tourSinglePageData['hero'];
    $rating = $tourSinglePageData['rating'];
    $priceBlock = $tourSinglePageData['priceBlock'];
    $flightInfo = $tourSinglePageData['flightInfo'];
    $buttons = $tourSinglePageData['buttons'];
    $slides = $tourSinglePageData['imageSlider'];
    $tabs = $tourSinglePageData['tabs'];
    $features = $tourSinglePageData['tourFeatures'];
    $cta = $tourSinglePageData['cta'];
    $starCount = $rating['reviewBlock']['starRating'] ?: 5;

    // Build section classes
    $section_classes = 'hero-section inner-hero-section single-tour-hero';

    // Resolve asset URIs
    $star_uri = get_theme_file_uri('/resources/images/star.svg');
    $check_green_uri = get_theme_file_uri('/resources/images/check-green.svg');
    $map_pin_uri = get_theme_file_uri('/resources/images/map-pin.svg');
    $clock_uri = get_theme_file_uri('/resources/images/blue-clock.svg');
    $location_check_uri = get_theme_file_uri('/resources/images/loaction-check.svg');

    // Rating value (clamp 1–5)
    $rating_value = !empty($rating['selectRating']) ? max(1, min(5, absint($rating['selectRating']))) : 0;

    $tourID = get_the_ID();

    $terms = get_the_terms($tourID, 'tour_category');

    // Pre-compute a unique, URL-safe slug for each tab
    $tabSlugs = [];
    foreach ($tabs as $i => $tab) {
        $slug = sanitize_title($tab['title']);
        $tabSlugs[] = $slug !== '' ? $slug : 'tab-' . ($i + 1);
    }

    $resolveTermName = static function ($term): string {
        if (is_object($term)) {
            return $term->name ?? '';
        }
        if (is_array($term)) {
            return $term['name'] ?? '';
        }
        if (is_numeric($term)) {
            $t = get_term((int) $term);
            return $t && !is_wp_error($t) ? $t->name : '';
        }
        return '';
    };

    $normalizeTerms = static function ($field) use ($resolveTermName): array {
        if (empty($field)) {
            return [];
        }
        // A single associative array counts as one term, not a list
        $items = is_array($field) && !isset($field['name']) ? $field : [$field];
        $names = [];
        foreach ($items as $term) {
            $name = $resolveTermName($term);
            if ($name !== '') {
                $names[] = $name;
            }
        }
        return $names;
    };

    $priceTags = [];
    $bookingTag = [];

    if (!empty($priceBlock['selectPriceTag']) && is_object($priceBlock['selectPriceTag'])) {
        $termId = $priceBlock['selectPriceTag']->term_id;
        $tptIconId = get_term_meta($termId, 'tpt_icon', true);
        $tptColor = get_term_meta($termId, 'tpt_color', true);
        $textColor = get_term_meta($termId, 'tpt_text_color', true);
        $tooltipText = get_term_meta($termId, 'tpt_tooltip_text', true);

        $priceTags[] = [
            'name' => $priceBlock['selectPriceTag']->name,
            'slug' => $priceBlock['selectPriceTag']->slug,
            'color' => !empty($tptColor) ? sanitize_hex_color($tptColor) : '',
            'text_color' => !empty($textColor) ? sanitize_hex_color($textColor) : '',
            'tooltip_text' => !empty($tooltipText) ? $tooltipText : '',
            'icon_url' => !empty($tptIconId)
                ? (filter_var($tptIconId, FILTER_VALIDATE_URL)
                    ? esc_url_raw($tptIconId)
                    : wp_get_attachment_image_url(absint($tptIconId), 'full'))
                : '',
        ];
    }

    if (!empty($priceBlock['selectBookingTag']) && is_object($priceBlock['selectBookingTag'])) {
        $termId = $priceBlock['selectBookingTag']->term_id;
        $tptIconId = get_term_meta($termId, 'tpt_icon', true);
        $tptColor = get_term_meta($termId, 'tpt_color', true);
        $textColor = get_term_meta($termId, 'tpt_text_color', true);
        $tooltipText2 = get_term_meta($termId, 'tpt_tooltip_text', true);

        $bookingTag[] = [
            'name' => $priceBlock['selectBookingTag']->name,
            'slug' => $priceBlock['selectBookingTag']->slug,
            'color' => !empty($tptColor) ? sanitize_hex_color($tptColor) : '',
            'text_color' => !empty($textColor) ? sanitize_hex_color($textColor) : '',
            'tooltip_text' => !empty($tooltipText2) ? $tooltipText2 : '',
            'icon_url' => !empty($tptIconId)
                ? (filter_var($tptIconId, FILTER_VALIDATE_URL)
                    ? esc_url_raw($tptIconId)
                    : wp_get_attachment_image_url(absint($tptIconId), 'full'))
                : '',
        ];
    }

    // Book-now link (ACF link field returns ['url', 'title', 'target'])
    $bookNowUrl = !empty($buttons['bookNow']['url']) ? esc_url($buttons['bookNow']['url']) : '';
    $bookNowTitle = !empty($buttons['bookNow']['title']) ? $buttons['bookNow']['title'] : __('Book Now', 'fly-venture');
    $bookNowTarget = !empty($buttons['bookNow']['target']) ? $buttons['bookNow']['target'] : '_self';

@endphp


<section class="{{ $section_classes }}">

    {{-- Background --}}
    <div class="hero-bg">

        {{-- Featured Image (desktop) --}}
        @if (!empty($hero['featuredImageUrl']))
            <img src="{{ esc_url($hero['featuredImageUrl']) }}" alt="{{ esc_attr($hero['title'] ?? 'Hero background') }}"
                class="h-full w-full object-cover desktop-img max-767:hidden!" loading="eager">
        @endif

    </div>

    <div class="hero-overlay"></div>

    <div class="container-fluid h-full">
        <div class="hero-content-wrap">
            <div class="hero-content-left fadeText">

                {{-- Rating Stars + Text --}}
                @if ($rating_value > 0 || !empty($rating['ratingText']))
                    <div class="hero-rating">

                        @if ($rating_value > 0)
                            <ul class="hero-stars">
                                @for ($s = 0; $s < $rating_value; $s++)
                                    <li>
                                        <img src="{{ esc_url($star_uri) }}" height="16" width="17"
                                            alt="star">
                                    </li>
                                @endfor
                            </ul>
                        @endif

                        @if (!empty($rating['ratingText']))
                            <p>{{ $rating['ratingText'] }}</p>
                        @endif

                    </div>
                @endif

                {{-- Tour Title --}}
                @if (!empty($hero['title']))
                    <div class="title title-white">
                        <h1>{!! $hero['title'] !!}</h1>
                    </div>
                @endif

                {{-- Short Description --}}
                @if (!empty($hero['shortDescription']))
                    <div class="content white mt-20">
                        {!! wp_kses_post($hero['shortDescription']) !!}
                    </div>
                @endif

            </div>
        </div>
    </div>

</section>


<section class="tour-overview-section py-40 lg:py-100 bg-[#F5F9FC]">
    <div class="container-fluid">
        <div class="grid max-1023:flex max-1023:flex-col-reverse max-1023:gap-30 grid-cols-12  items-start">

            <div
                class="lg:col-span-8 w-full flex flex-col gap-24 bg-white p-40 rounded-lg shadow-[0px_0px_16px_rgba(0,0,0,0.16)] max-1600:p-24">

                {{-- ── Highlighted nav bar ──────────────────────────────────────────── --}}
                <div
                    class="higlited-nav flex flex-wrap items-center justify-between gap-8 992:gap-15 1441:gap-24 max-575:flex-col max-575:justify-start max-575:items-start text-body-3 font-medium text-black">

                    {{-- Star rating + review count --}}
                    @if ($rating['reviewBlock']['starRating'] > 0 || !empty($rating['reviewBlock']['reviewText']))

                        <div class="flex items-center gap-7">
                            @if ($rating['reviewBlock']['starRating'] > 0)
                                <?php echo flyventure_render_svg_rating($rating['reviewBlock']['starRating'], $tourID); ?>
                                @if (!empty($rating['reviewBlock']['reviewText']))
                                    <p> <?php echo wp_kses_post($rating['reviewBlock']['reviewText']); ?></p>
                                @endif
                            @endif
                        </div>
                    @endif

                    {{-- Recommended text --}}
                    @if (!empty($hero['recommendedText']))
                        <div class="flex items-center gap-6">
                            <img src="{{ esc_url($check_green_uri) }}"
                                alt="{{ esc_attr__('recommended', 'fly-venture') }}" class="w-16 h-16">
                            <p>{!! wp_kses_post($hero['recommendedText']) !!}</p>
                        </div>
                    @endif

                    {{-- Departs from --}}
                    @if (!empty($hero['departsFromText']))
                        <div class="flex items-center gap-6">
                            <img src="{{ esc_url($map_pin_uri) }}" alt="{{ esc_attr__('location', 'fly-venture') }}"
                                class="w-16 h-16">
                            <p>{{ esc_html($hero['departsFromText']) }}
                                @if (is_array($terms) && !empty($terms[0]->name))
                                    <span>{{ esc_html($terms[0]->name) }}</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    {{-- Flight duration --}}
                    @if (!empty($flightInfo['duration']))
                        <div class="flex items-center gap-6">
                            <img src="{{ esc_url($clock_uri) }}" alt="{{ esc_attr__('duration', 'fly-venture') }}"
                                class="w-16 h-16">
                            <p><span>{{ esc_html($flightInfo['duration']) }}</span> minutes</p>
                        </div>
                    @endif

                </div>
                {{-- ── End highlighted nav ─────────────────────────────────────────── --}}

                {{-- ── Gallery ─────────────────────────────────────────────────────── --}}
                @if (!empty($tourSinglePageData['imageSlider']))
                    <div
                        class="garely-grid pt-8 grid grid-cols-1 gap-12 lg:grid-cols-[1fr_100px] sm:gap-20 xl:grid-cols-[1fr_180px] max-479:gap-12 h-auto 1199:h-full 1199:max-h-449">

                        {{-- Main / top slider --}}
                        <div class="eco-gallery-top swiper w-full h-300 sm:h-full overflow-hidden rounded-lg">
                            <div class="swiper-wrapper">
                                @foreach ($tourSinglePageData['imageSlider'] as $slide)
                                    @php
                                        $slideUrl = esc_url($slide['image']['url'] ?? '');
                                        $slideAlt = esc_attr($slide['image']['alt'] ?? '');
                                    @endphp
                                    @if ($slideUrl)
                                        <div class="swiper-slide">
                                            <img src="{{ $slideUrl }}" alt="{{ $slideAlt }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Thumbnail / thumbs slider --}}
                        <div class="eco-gallery-thumbs swiper w-full overflow-hidden rounded-lg">
                            <div class="swiper-wrapper">
                                @foreach ($tourSinglePageData['imageSlider'] as $slide)
                                    @php
                                        $slideUrl = esc_url($slide['image']['url'] ?? '');
                                        $slideAlt = esc_attr($slide['image']['alt'] ?? '');
                                    @endphp
                                    @if ($slideUrl)
                                        <div class="swiper-slide">
                                            <img src="{{ $slideUrl }}" alt="{{ $slideAlt }}"
                                                class="w-full max-639:h-full h-[calc(33.33%-8px)] max-1199:h-[calc(33.33%-8px)] object-cover rounded-lg">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endif
                {{-- ── End gallery ─────────────────────────────────────────────────── --}}

                {{-- ── Tab navigation ─────────────────────────────────────────────── --}}
                @if (!empty($tabs))
                    <div
                        class="tab-nav border border-lightblue px-24 py-20 max-1441:py-16 max-1441:px-20 max-1199:py-14 max-1199:px-16 rounded-lg">
                        <ul role="list">
                            @foreach ($tabs as $i => $tab)
                                @if (!empty($tab['title']))
                                    <li>
                                        <button type="button" class="tab-btn"
                                            data-target="{{ esc_attr($tabSlugs[$i]) }}">{{ esc_html(mb_strtoupper($tab['title'])) }}</button>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- ── End tab navigation ──────────────────────────────────────────── --}}


                {{-- ── Tab content panels ─────────────────────────────────────────── --}}
                @foreach ($tabs as $i => $tab)
                    <div class="tab-content w-full" data-tab="{{ esc_attr($tabSlugs[$i]) }}">

                        @if (!empty($tab['title']))
                            <div class="title title-blue mb-20">
                                <h2>{{ esc_html($tab['title']) }}</h2>
                            </div>
                        @endif

                        @if ($tab['isLocation'] && !empty($tab['locations']))
                            {{-- Location grid panel --}}
                            <div class="content">
                                @if (!empty($tab['content']))
                                    {!! wp_kses_post($tab['content']) !!}
                                @endif
                                @php
                                    $visibleLocations = array_filter(
                                        $tab['locations'],
                                        fn($loc) => !empty($loc['title']) ||
                                            !empty($loc['icon']['url'] ?? '') ||
                                            !empty($loc['shortDescription']) ||
                                            !empty($loc['buttonLink']['url'] ?? ''),
                                    );
                                @endphp
                                @if (!empty($visibleLocations))
                                    <div class="location-grid mt-24 grid grid-cols-2 gap-16 max-575:grid-cols-1">
                                        @foreach ($visibleLocations as $location)
                                            @php
                                                $locIconUrl = esc_url($location['icon']['url'] ?? '');
                                                $locIconAlt = esc_attr($location['icon']['alt'] ?? '');
                                                $locTitle = $location['title'];
                                                $locDesc = $location['shortDescription'];
                                                $locLink = $location['buttonLink'];
                                            @endphp
                                            <div class="box">
                                                @if (!empty($locTitle) || $locIconUrl)
                                                    <div class="title title-black">
                                                        <h4>
                                                            @if ($locIconUrl)
                                                                <img src="{{ $locIconUrl }}"
                                                                    alt="{{ $locIconAlt }}" height="20"
                                                                    width="20">
                                                            @endif
                                                            {{ esc_html($locTitle) }}
                                                        </h4>
                                                    </div>
                                                @endif
                                                @if (!empty($locDesc) || !empty($locLink['url']))
                                                    <div class="box-inner">
                                                        @if (!empty($locDesc))
                                                            <p>{!! wp_kses_post($locDesc) !!}</p>
                                                        @endif
                                                        @if (!empty($locLink['url']))
                                                            <a href="{{ esc_url($locLink['url']) }}" class="blue-link"
                                                                role="link"
                                                                @if (($locLink['target'] ?? '') === '_blank') target="_blank"
                              rel="noopener noreferrer" @endif>{{ esc_html($locLink['title'] ?? __('Get Directions', 'fly-venture')) }}</a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @elseif ($tab['isMap'] && !empty($tab['twc_map_image']))
                            {{-- Map panel --}}
                            <div class="content">
                                @if (!empty($tab['content']))
                                    {!! wp_kses_post($tab['content']) !!}
                                @endif
                                @php $mapField = $tab['twc_map_image']; @endphp
                                @if (!empty($mapField['url']))
                                    {{-- ACF image field used as map graphic --}}
                                    <div class="map-img mt-24">
                                        <img src="{{ esc_url($mapField['url']) }}"
                                            alt="{{ esc_attr($mapField['alt'] ?? __('Route map', 'fly-venture')) }}"
                                            width="{{ absint($mapField['width'] ?? 1120) }}"
                                            height="{{ absint($mapField['height'] ?? 1324) }}"
                                            class="h-full w-full rounded-lg">
                                    </div>
                                @elseif (!empty($mapField['lat']) && !empty($mapField['lng']))
                                    {{-- ACF Google Map field --}}
                                    @php
                                        $mapQuery = !empty($mapField['address'])
                                            ? urlencode($mapField['address'])
                                            : urlencode($mapField['lat'] . ',' . $mapField['lng']);
                                    @endphp
                                    <div class="map-img mt-24">
                                        <iframe
                                            src="{{ esc_url('https://maps.google.com/maps?q=' . $mapQuery . '&output=embed') }}"
                                            class="h-full w-full rounded-lg" width="600" height="450"
                                            style="border:0;" allowfullscreen="" loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade"
                                            title="{{ esc_attr($tab['title'] ?? __('Route Map', 'fly-venture')) }}"></iframe>
                                    </div>
                                @endif
                            </div>
                        @else
                            {{-- Regular WYSIWYG content panel --}}
                            @if (!empty($tab['content']))
                                <div class="content">
                                    {!! wp_kses_post($tab['content']) !!}
                                </div>
                            @endif
                        @endif

                    </div>
                @endforeach
                {{-- ── End tab content panels ───────────────────────────────────────── --}}

            </div>

            {{-- ── Right sidebar ───────────────────────────────────────────────── --}}
            <div class="lg:col-span-4 w-full lg:sticky top-100 pl-40 max-1441:pl-30 max-1023:pl-0">
                <div
                    class="bg-white rounded-lg shadow-[0px_0px_16px_rgba(0,0,0,0.16)] border border-gray-100 p-40 max-1600:p-24 max-1199:p-20 w-full flex flex-col">

                    {{-- Price block --}}
                    <div class="popular-tour-meta">

                        @if (!empty($priceBlock['regularPrice']))
                            <small>{{ esc_html__('From', 'fly-venture') }}
                                <span>{{ esc_html($priceBlock['regularPrice']) }}</span></small>
                        @endif

                        @if (!empty($priceBlock['offerPrice']) || !empty($priceBlock['perPersonText']))
                            <div class="flex items-baseline gap-8">
                                @if (!empty($priceBlock['offerPrice']))
                                    <strong>{{ esc_html__('Now', 'fly-venture') }}
                                        {{ esc_html($priceBlock['offerPrice']) }}</strong>
                                @endif
                                @if (!empty($priceBlock['perPersonText']))
                                    <div class="normal-tag">
                                        <span>{{ esc_html($priceBlock['perPersonText']) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif


                        @if (!empty($priceTags) || !empty($bookingTag))
                            <div class="flex flex-wrap gap-x-14 gap-y-0 mt-8">

                                @if (!empty($priceTags))
                                    <div class="best-price-tag price-tag-item" style="<?php echo !empty($priceTags[0]['color']) ? 'background-color:' . esc_attr($priceTags[0]['color']) . ';' : ''; ?>">

                                        <?php if (!empty($priceTags[0]['icon_url'])) : ?>
                                        <img src="<?php echo esc_url($priceTags[0]['icon_url']); ?>" height="14" width="14"
                                            alt="<?php echo esc_attr($priceTags[0]['name']); ?>">
                                        <?php endif; ?>

                                        <span style="color:<?php echo esc_attr(sanitize_hex_color($priceTags[0]['text_color'])); ?>;"><?php echo esc_html($priceTags[0]['name']); ?></span>

                                        @if (!empty($priceTags[0]['tooltip_text']))
                                            <div class="price-tag-tooltip-wrap">
                                                <svg width="10" height="10" viewBox="0 0 10 10"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M5.3565 7.3565C5.45217 7.2605 5.5 7.14167 5.5 7V5C5.5 4.85833 5.452 4.73967 5.356 4.644C5.26 4.54833 5.14133 4.50033 5 4.5C4.85867 4.49967 4.74 4.54767 4.644 4.644C4.548 4.74033 4.5 4.859 4.5 5V7C4.5 7.14167 4.548 7.2605 4.644 7.3565C4.74 7.4525 4.85867 7.50033 5 7.5C5.14133 7.49967 5.26017 7.45217 5.3565 7.3565ZM5.3565 3.356C5.45217 3.26033 5.5 3.14167 5.5 3C5.5 2.85833 5.452 2.73967 5.356 2.644C5.26 2.54833 5.14133 2.50033 5 2.5C4.85867 2.49967 4.74 2.54767 4.644 2.644C4.548 2.74033 4.5 2.859 4.5 3C4.5 3.141 4.548 3.25983 4.644 3.3565C4.74 3.45317 4.85867 3.501 5 3.5C5.14133 3.499 5.26017 3.451 5.3565 3.356ZM5 10C4.30833 10 3.65833 9.86867 3.05 9.606C2.44167 9.34333 1.9125 8.98717 1.4625 8.5375C1.0125 8.08783 0.656334 7.55867 0.394001 6.95C0.131667 6.34133 0.000333966 5.69133 6.32911e-07 5C-0.0003327 4.30867 0.131001 3.65867 0.394001 3.05C0.657001 2.44133 1.01317 1.91217 1.4625 1.4625C1.91183 1.01283 2.441 0.656667 3.05 0.394C3.659 0.131333 4.309 0 5 0C5.691 0 6.341 0.131333 6.95 0.394C7.559 0.656667 8.08817 1.01283 8.5375 1.4625C8.98683 1.91217 9.34317 2.44133 9.6065 3.05C9.86983 3.65867 10.001 4.30867 10 5C9.999 5.69133 9.86767 6.34133 9.606 6.95C9.34433 7.55867 8.98817 8.08783 8.5375 8.5375C8.08683 8.98717 7.55767 9.3435 6.95 9.6065C6.34233 9.8695 5.69233 10.0007 5 10ZM5 9C6.11667 9 7.0625 8.6125 7.8375 7.8375C8.6125 7.0625 9 6.11667 9 5C9 3.88333 8.6125 2.9375 7.8375 2.1625C7.0625 1.3875 6.11667 1 5 1C3.88333 1 2.9375 1.3875 2.1625 2.1625C1.3875 2.9375 1 3.88333 1 5C1 6.11667 1.3875 7.0625 2.1625 7.8375C2.9375 8.6125 3.88333 9 5 9Z"
                                                        fill="<?php echo esc_attr(sanitize_hex_color($priceTags[0]['text_color'])); ?>" />
                                                </svg>

                                                @if (!empty($priceTags[0]['tooltip_text']))
                                                    <div class="price-tag-tooltip">
                                                        <?php echo esc_html($priceTags[0]['tooltip_text']); ?>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                @endif

                                @if (!empty($bookingTag))
                                    <div class="best-price-tag price-tag-item" style="<?php echo !empty($bookingTag[0]['color']) ? 'background-color:' . esc_attr($bookingTag[0]['color']) . ';' : ''; ?>">

                                        <?php if (!empty($bookingTag[0]['icon_url'])) : ?>
                                        <img src="<?php echo esc_url($bookingTag[0]['icon_url']); ?>" height="14" width="14"
                                            alt="<?php echo esc_attr($bookingTag[0]['name']); ?>">
                                        <?php endif; ?>

                                        <span style="color:<?php echo esc_attr(sanitize_hex_color($bookingTag[0]['text_color'])); ?>;"><?php echo esc_html($bookingTag[0]['name']); ?></span>

                                        @if (!empty($bookingTag[0]['tooltip_text']))
                                            <div class="price-tag-tooltip-wrap">
                                                <svg width="10" height="10" viewBox="0 0 10 10"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M5.3565 7.3565C5.45217 7.2605 5.5 7.14167 5.5 7V5C5.5 4.85833 5.452 4.73967 5.356 4.644C5.26 4.54833 5.14133 4.50033 5 4.5C4.85867 4.49967 4.74 4.54767 4.644 4.644C4.548 4.74033 4.5 4.859 4.5 5V7C4.5 7.14167 4.548 7.2605 4.644 7.3565C4.74 7.4525 4.85867 7.50033 5 7.5C5.14133 7.49967 5.26017 7.45217 5.3565 7.3565ZM5.3565 3.356C5.45217 3.26033 5.5 3.14167 5.5 3C5.5 2.85833 5.452 2.73967 5.356 2.644C5.26 2.54833 5.14133 2.50033 5 2.5C4.85867 2.49967 4.74 2.54767 4.644 2.644C4.548 2.74033 4.5 2.859 4.5 3C4.5 3.141 4.548 3.25983 4.644 3.3565C4.74 3.45317 4.85867 3.501 5 3.5C5.14133 3.499 5.26017 3.451 5.3565 3.356ZM5 10C4.30833 10 3.65833 9.86867 3.05 9.606C2.44167 9.34333 1.9125 8.98717 1.4625 8.5375C1.0125 8.08783 0.656334 7.55867 0.394001 6.95C0.131667 6.34133 0.000333966 5.69133 6.32911e-07 5C-0.0003327 4.30867 0.131001 3.65867 0.394001 3.05C0.657001 2.44133 1.01317 1.91217 1.4625 1.4625C1.91183 1.01283 2.441 0.656667 3.05 0.394C3.659 0.131333 4.309 0 5 0C5.691 0 6.341 0.131333 6.95 0.394C7.559 0.656667 8.08817 1.01283 8.5375 1.4625C8.98683 1.91217 9.34317 2.44133 9.6065 3.05C9.86983 3.65867 10.001 4.30867 10 5C9.999 5.69133 9.86767 6.34133 9.606 6.95C9.34433 7.55867 8.98817 8.08783 8.5375 8.5375C8.08683 8.98717 7.55767 9.3435 6.95 9.6065C6.34233 9.8695 5.69233 10.0007 5 10ZM5 9C6.11667 9 7.0625 8.6125 7.8375 7.8375C8.6125 7.0625 9 6.11667 9 5C9 3.88333 8.6125 2.9375 7.8375 2.1625C7.0625 1.3875 6.11667 1 5 1C3.88333 1 2.9375 1.3875 2.1625 2.1625C1.3875 2.9375 1 3.88333 1 5C1 6.11667 1.3875 7.0625 2.1625 7.8375C2.9375 8.6125 3.88333 9 5 9Z"
                                                        fill="<?php echo esc_attr(sanitize_hex_color($bookingTag[0]['text_color'])); ?>" />
                                                </svg>

                                                @if (!empty($bookingTag[0]['tooltip_text']))
                                                    <div class="price-tag-tooltip">
                                                        <?php echo esc_html($bookingTag[0]['tooltip_text']); ?>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                @endif

                            </div>
                        @endif

                    </div>

                    {{-- Passenger offer text --}}
                    @if (!empty($hero['passengerOfferText']))
                        <div
                            class="text-black font-normal text-heading-5 leading-23 mt-14 max-1441:text-body-1 max-1199:text-body-2">
                            {{ esc_html($hero['passengerOfferText']) }}
                        </div>
                    @endif

                    {{-- Book now CTA --}}
                    @if ($bookNowUrl)
                        <a href="{{ $bookNowUrl }}" @if ($bookNowTarget === '_blank') target="_blank" @endif
                            class="btn btn-orange w-full py-16! text-body-3! leading-16! text-white font-semibold rounded-lg uppercase mt-24 text-center  btn-teal"
                            data-fareharbor-lightframe="yes" tabindex="0"
                            role="link">{!! esc_html($bookNowTitle) !!}</a>
                    @else
                        <button type="button"
                            class="btn btn-orange w-full py-16! text-body-3! leading-16! text-white font-semibold rounded-lg uppercase mt-24">{{ esc_html__('Book Now', 'fly-venture') }}</button>
                    @endif

                    {{-- Tour feature highlights --}}
                    @if (!empty($features))
                        <div
                            class="grid grid-cols-2 gap-y-24 gap-x-10 mt-24 max-1365:grid-cols-1 max-1365:gap-y-10 max-1023:grid-cols-2 max-575:grid-cols-1">
                            @foreach ($features as $feature)
                                @php
                                    $featureIconUrl = esc_url($feature['icon']['url'] ?? '');
                                    $featureIconAlt = esc_attr($feature['icon']['alt'] ?? '');
                                    $featureText = $feature['text'];
                                @endphp
                                @if (!empty($featureText))
                                    <div
                                        class="flex items-center gap-8 max-1441:gap-4 text-body-2 max-1441:text-body-3 font-medium leading-24 text-[#008236]">
                                        @if ($featureIconUrl)
                                            <img src="{{ $featureIconUrl }}" alt="{{ $featureIconAlt }}"
                                                class="w-20 h-20">
                                        @else
                                            <img src="{{ esc_url($location_check_uri) }}" alt=""
                                                class="w-20 h-20" aria-hidden="true">
                                        @endif
                                        {!! wp_kses_post($featureText) !!}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <hr class="border-t border-[#EEEEEE] mt-24">
                    @endif

                    {{-- Calendar / booking widget placeholder --}}
                    @php
                      $shortcode_booking = $hero['shortcodeBooking'] ?? '';
                    @endphp
                    @if ( ! empty( $shortcode_booking ) )
                        <div class="calendar-widget w-full">
                            {!! do_shortcode($shortcode_booking) !!}
                        </div>
                    @endif                                                            
                </div>
            </div>
            {{-- ── End right sidebar ───────────────────────────────────────────── --}}

        </div>
    </div>
</section>



<!-- full-img-content-start -->
@php
    $ctaBgUrl = esc_url($cta['backgroundImage']['url'] ?? '');
    $ctaBgAlt = esc_attr($cta['backgroundImage']['alt'] ?? '');
    $ctaBgW = absint($cta['backgroundImage']['width'] ?? 977);
    $ctaBgH = absint($cta['backgroundImage']['height'] ?? 438);
    $ctaIconUrl = esc_url($cta['iconImage']['url'] ?? '');
    $ctaIconAlt = esc_attr($cta['iconImage']['alt'] ?? '');
    $ctaIconW = absint($cta['iconImage']['width'] ?? 180);
    $ctaIconH = absint($cta['iconImage']['height'] ?? 169);
    $ctaTitle = $cta['title'] ?? '';
    $ctaDesc = $cta['shortDescription'] ?? '';
    $ctaBtnUrl = esc_url($cta['bookNowButton']['url'] ?? '');
    $ctaBtnTitle = $cta['bookNowButton']['title'] ?? __('Book Now', 'fly-venture');
    $ctaBtnTarget = ($cta['bookNowButton']['target'] ?? '') === '_blank' ? '_blank' : '_self';
@endphp
@if ($ctaBgUrl || $ctaTitle || $ctaDesc || $ctaBtnUrl || $ctaIconUrl)
    <section class="full-img-content-wrap py-100 max-1023:py-40">
        <div class="container-fluid">
            <div class="full-img-conatent">

                {{-- Background / media image --}}
                @if ($ctaBgUrl)
                    <div class="full-img-conatent-media">
                        <img src="{{ $ctaBgUrl }}" height="{{ $ctaBgH }}" width="{{ $ctaBgW }}"
                            alt="{{ $ctaBgAlt }}">
                    </div>
                @endif

                {{-- Text content --}}
                @if ($ctaTitle || $ctaDesc || $ctaBtnUrl)
                    <div class="full-img-conatent-content fadeText">
                        @if ($ctaTitle)
                            <div class="title title-blue">
                                <h2>{{ esc_html($ctaTitle) }}</h2>
                            </div>
                        @endif

                        @if ($ctaDesc)
                            <div class="content">
                                {!! wp_kses_post($ctaDesc) !!}
                            </div>
                        @endif

                        @if ($ctaBtnUrl)
                            <div class="btn-custom mt-25">
                                <a href="{{ $ctaBtnUrl }}"
                                    @if ($ctaBtnTarget === '_blank') target="_blank" rel="noopener noreferrer" @endif
                                    class="btn btn-orange" aria-label="{{ esc_attr($ctaBtnTitle) }}">
                                    {{ esc_html($ctaBtnTitle) }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Decorative icon --}}
                @if ($ctaIconUrl)
                    <div class="full-img-conatent-icon" aria-hidden="true">
                        <img src="{{ $ctaIconUrl }}" height="{{ $ctaIconH }}" width="{{ $ctaIconW }}"
                            alt="{{ $ctaIconAlt }}">
                    </div>
                @endif

            </div>
        </div>
    </section>
@endif {{-- CTA section --}}
