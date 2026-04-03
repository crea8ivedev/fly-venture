@php
    $section_classes = 'package-map-section py-100 max-1199:py-60 max-767:py-40';
    if (!empty($content->hide_section) && $content->hide_section !== 'no') {
        $section_classes .= ' hidden';
    }
    if (!empty($content->class) && is_scalar($content->class)) {
        $section_classes .= ' ' . $content->class;
    }
    $section_id = (!empty($content->id) && is_scalar($content->id)) ? $content->id : '';

    $icon     = $content->adventure_icon ?? null;
    $btn      = $content->button ?? null;
    $counties = (!empty($content->counties) && is_array($content->counties)) ? $content->counties : [];
    $svg_paths = $content->svg_paths ?? [];
    $svg_abbrs = $content->svg_abbrs ?? [];

    // Default county: first with is_default = true, fallback to first row
    $default_county = null;
    foreach ($counties as $row) {
        if (!empty($row['is_default'])) {
            $default_county = $row;
            break;
        }
    }
    if (!$default_county && !empty($counties)) {
        $default_county = $counties[0];
    }
@endphp

<section
    class="{{ $section_classes }}"
    @if(!empty($section_id)) id="{{ esc_attr($section_id) }}" @endif
>
    <div class="container-fluid">
        <div class="package-map-inner">

            <!-- Left: Content -->
            <div class="package-map-content">
                <div class="flex flex-col gap-20">

                    @if(!empty($icon))
                        <div class="adventure-icon" aria-hidden="true">
                            @if(is_array($icon) && !empty($icon['url']))
                                <img
                                    src="{{ esc_url($icon['url']) }}"
                                    width="{{ !empty($icon['width']) ? absint($icon['width']) : 90 }}"
                                    height="{{ !empty($icon['height']) ? absint($icon['height']) : 100 }}"
                                    alt="{{ esc_attr($icon['alt'] ?? '') }}"
                                >
                            @elseif(is_string($icon) && $icon !== '')
                                <img src="{{ esc_url($icon) }}" width="90" height="100" alt="">
                            @endif
                        </div>
                    @endif

                    @if(!empty($content->title))
                        <div class="title title-blue">
                            <h2>{!! wp_kses_post($content->title) !!}</h2>
                        </div>
                    @endif

                    @if(!empty($content->sub_title))
                        <div class="content content-black">
                            <p>{{ esc_html($content->sub_title) }}</p>
                        </div>
                    @endif

                </div>

                <div class="county-row">
                    <span class="county-row-label">County:</span>
                    <div class="county-pill-display">
                        <span id="selected-county-name">{{ esc_html($default_county['county_name'] ?? '') }}</span>
                    </div>
                </div>

                <div class="package-price">
                    <div class="title title-blue">
                        <h3 id="selected-county-price">{{ esc_html($default_county['county_price'] ?? '') }}</h3>
                    </div>
                </div>

                @if(!empty($btn) && is_array($btn) && !empty($btn['url']))
                    <a
                        href="{{ esc_url($btn['url']) }}"
                        class="btn btn-orange w-max"
                        role="link"
                        target="{{ esc_attr($btn['target'] ?? '_self') }}"
                        aria-label="{{ esc_attr($btn['title'] ?? 'Book Now') }}"
                    >{{ esc_html($btn['title'] ?? 'Book Now') }}</a>
                @endif
            </div>

            <!-- Right: Interactive SVG County Map -->
            <div class="package-map-visual">
                <svg id="county-map-svg" viewBox="0 0 768 908" xmlns="http://www.w3.org/2000/svg"
                    aria-label="Interactive county map — click a county to see pricing">

                    <defs>
                        <filter id="county-shadow" x="-10%" y="-10%" width="120%" height="120%">
                            <feDropShadow dx="0" dy="0" stdDeviation="5" flood-color="rgba(40,114,163,0.35)" />
                        </filter>
                    </defs>

                    @foreach($counties as $row)
                        @php
                            $key        = $row['county_key'] ?? '';
                            $name       = $row['county_name'] ?? '';
                            $price      = $row['county_price'] ?? '';
                            $path       = $svg_paths[$key] ?? null;
                            $abbr       = $svg_abbrs[$key] ?? strtoupper(substr($key, 0, 3));
                            $is_default = !empty($row['is_default']);
                        @endphp

                        @if($path)
                            <g
                                class="county-group{{ $is_default ? ' county-active' : '' }}"
                                data-county="{{ esc_attr($key) }}"
                                data-name="{{ esc_attr($name) }}"
                                data-price="{{ esc_attr($price) }}"
                            >
                                <path class="county-path" d="{{ $path }}" />
                                <text
                                    class="county-abbr-label{{ $is_default ? ' county-abbr-hidden' : '' }}"
                                    data-for="{{ esc_attr($key) }}"
                                >{{ $abbr }}</text>
                            </g>
                        @endif
                    @endforeach

                    <!-- Floating tooltip pill (JS-controlled) -->
                    <g id="county-tooltip-pill" aria-hidden="true">
                        <rect id="tooltip-pill-rect" rx="18" fill="white" />
                        <text id="tooltip-pill-text" text-anchor="middle" dominant-baseline="central"
                            font-family="'Work Sans', sans-serif" font-weight="600" font-size="18"
                            fill="#2872A3"></text>
                    </g>

                </svg>
            </div>

        </div>
    </div>
</section>
