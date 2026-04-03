@if($content->hide_section !== 'yes')
@php
  // Group gift_card_options by location term ID, resolve term objects
  $grouped_cards = [];
  $tab_terms     = [];
  if (!empty($content->gift_card_options)) {
    foreach ($content->gift_card_options as $card) {
      $raw_location = $card['location'] ?? 0;
      $term_id = is_array($raw_location) ? (int)($raw_location[0] ?? 0) : (int)$raw_location;
      if (!$term_id) continue;
      if (!isset($tab_terms[$term_id])) {
        $term = get_term($term_id);
        if ($term && !is_wp_error($term)) {
          $tab_terms[$term_id] = $term;
          $grouped_cards[$term_id] = [];
        }
      }
      if (isset($grouped_cards[$term_id])) {
        $grouped_cards[$term_id][] = $card;
      }
    }
  }
  $first_term_id = array_key_first($tab_terms);

  // Map tab slug to per-tab description and button fields
  $tab_descriptions = [
    'tampa'    => $content->tampa_description ?? null,
    'sarasota' => $content->sarasota_description ?? null,
    'st-pete'  => $content->st_pete_description ?? null,
  ];
  $tab_buttons = [
    'tampa'    => $content->tampa_button ?? null,
    'sarasota' => $content->sarasota_button ?? null,
    'st-pete'  => $content->stpete_button ?? null,
  ];
@endphp
<section
  id="{{ $content->id ?? '' }}"
  class="ctm-popular-gift py-100 max-1199:py-60 max-767:py-40 {{ $content->class ?? '' }}"
  @if(!empty($content->background_color)) style="background-color: {{ esc_attr($content->background_color) }};" @endif
>
  <div class="container-fluid">

    {{-- Section Header --}}
    <div class="gift-section-head fadeText">

      @if(!empty($content->icon))
        <div class="gift-section-icon">
          <img
            src="{{ esc_url($content->icon['url']) }}"
            height="{{ $content->icon['height'] ?? 100 }}"
            width="{{ $content->icon['width'] ?? 90 }}"
            alt="{{ esc_attr($content->icon['alt'] ?? '') }}"
          >
        </div>
      @endif

      @if(!empty($content->heading))
        <div class="title title-blue">
          <h2>{{ $content->heading }}</h2>
        </div>
      @endif

      @if(!empty($content->subtitle))
        <div class="content content-black">
          <p>{{ $content->subtitle }}</p>
        </div>
      @endif

      {{-- Tab Navigation - generated from unique location terms --}}
      @if(!empty($tab_terms))
        <div class="gift-card-tab-nav tab-nav">
          @foreach($tab_terms as $term_id => $term)
            <button
              class="gift-card-tab-btn tab-btn {{ $term_id === $first_term_id ? 'active' : '' }}"
              type="button"
              data-target="{{ sanitize_title($term->name) }}"
            >{{ esc_html($term->name) }}</button>
          @endforeach
        </div>
      @endif

    </div>

    {{-- Tab Panels --}}
    @foreach($tab_terms as $term_id => $term)
      @php
        $tab_slug   = sanitize_title($term->name);
        $term_link  = get_term_link($term);
        $cards      = $grouped_cards[$term_id] ?? [];
        $is_first   = $term_id === $first_term_id;
        $tab_desc   = $tab_descriptions[$tab_slug] ?? null;
        $tab_button = $tab_buttons[$tab_slug] ?? null;
      @endphp
      <div class="tab-content gift-card-panel {{ $is_first ? 'active' : 'hidden' }}" data-tab="{{ $tab_slug }}">

        @if(!empty($tab_desc))
          <p class="gift-card-panel-intro">{!! $tab_desc !!}</p>
        @endif

        {{-- Cards Grid --}}
        @if(!empty($cards))
          <div class="gift-cards-grid">
            @foreach($cards as $card)
              <div class="gift-card-item">

                @if(!empty($card['image']))
                  <img
                    src="{{ esc_url($card['image']['url']) }}"
                    class="gift-card-stamp-img"
                    alt="{{ esc_attr($card['image']['alt'] ?? '') }}"
                    width="{{ $card['image']['width'] ?? 602 }}"
                    height="{{ $card['image']['height'] ?? 412 }}"
                  >
                @endif

                @if(!empty($card['amount']))
                  <span class="gift-card-price">${{ esc_html($card['amount']) }}</span>
                @endif

                <div class="gift-card-info">
                  @if(!empty($card['gift_card_description']))
                    <p class="gift-card-tour-name">{{ esc_html($card['gift_card_description']) }}</p>
                  @endif

                  @if(!empty($card['button']))
                    <a
                      href="{{ esc_url($card['button']['url']) }}"
                      class="gift-card-learn-btn btn btn-b-white"
                      target="{{ $card['button']['target'] ?: '_self' }}"
                    >{{ esc_html($card['button']['title']) }}</a>
                  @endif
                </div>

              </div>
            @endforeach
          </div>
        @endif

        {{-- Footer Details --}}
        @if(!empty($content->gift_card_details))
          <div class="gift-card-panel-footer">
            {!! $content->gift_card_details !!}
          </div>
        @endif

        {{-- Panel Actions --}}
        <div class="gift-card-panel-actions">
          @if(!empty($tab_button))
            <a
              href="{{ esc_url($tab_button['url']) }}"
              class="btn btn-orange"
              target="{{ $tab_button['target'] ?: '_self' }}"
            >{{ esc_html($tab_button['title']) }}</a>
          @endif

          @if(!is_wp_error($term_link))
            <a href="{{ esc_url($term_link) }}" class="gift-card-learn-btn btn btn-b-white">
              View all {{ esc_html($term->name) }} Tours
            </a>
          @endif
        </div>

      </div>
    @endforeach

  </div>
</section>
@endif
