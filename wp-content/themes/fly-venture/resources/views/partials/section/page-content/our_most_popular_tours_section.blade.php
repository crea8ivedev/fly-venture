@php
  $allCategories = [];
  $selectedTourIds = [];
  if (!empty($content->select_tours) && is_array($content->select_tours)) {
      foreach ($content->select_tours as $tour) {
          $selectedTourIds[] = $tour->ID;
          $terms = get_the_terms($tour->ID, 'tour_category');
          if ($terms && !is_wp_error($terms)) {
              foreach ($terms as $term) {
                  $allCategories[$term->slug] = [
                      'name' => $term->name,
                      'link' => get_term_link($term),
                  ];
              }
          }
      }
  }

  // Map category slugs to their dedicated ACF button fields
  $categoryButtons = [
      'tampa'    => $content->view_all_tampa_tours_button    ?? null,
      'sarasota' => $content->view_all_sarasota_tours_button ?? null,
  ];

  $firstCategory = array_key_first($allCategories) ?? '';

  // Resolve the CTA for the first/active category
  $firstButton = $categoryButtons[$firstCategory] ?? null;
  $firstCategoryLink  = !empty($firstButton['url'])   ? esc_url($firstButton['url'])    : 'javascript:void(0);';
  $firstCategoryLabel = !empty($firstButton['title'])  ? $firstButton['title']           : 'VIEW ALL TOURS';
  $firstCategoryTarget = !empty($firstButton['target']) ? $firstButton['target']          : '_self';
  $selectedToursAttr = !empty($selectedTourIds) ? implode(',', $selectedTourIds) : '';
@endphp

  <!-- popular-tours-start -->
<section
  class="popular-tours-wrap  pt-80 pb-100 max-1023:py-40 bg-[#F5F9FC]"
  data-ajax-url="{{ esc_url(admin_url('admin-ajax.php')) }}"
  data-ajax-nonce="{{ esc_attr(wp_create_nonce('flyventure_popular_tours')) }}"
  data-selected-tours="{{ esc_attr($selectedToursAttr) }}"
  style="{{ !empty($content->background_color) ? 'background-color:' . esc_attr($content->background_color) . ';' : '' }}">
  <div class="container-fluid">
    <div class="popular-tours-grid">
      <div class="popular-tours-intro fadeText">

        @if (!empty($content->ompt_icon))
          <div class="icon">
            {!! wp_get_attachment_image($content->ompt_icon['ID'], 'full', false, [
                'height' => '100',
                'width'  => '63',
                'alt'    => 'Location icon',
                'class'  => 'w-full h-full object-contain max-w-100 max-h-100 max-1199:max-w-50 max-1199:max-h-50',
            ]) !!}
          </div>
        @endif

        @if (!empty($content->ompt_title))
          <div class="title title-blue mt-20 max-w-328">
            <h2>{{ $content->ompt_title }}</h2>
          </div>
        @endif

        {{-- Category filter tabs --}}
        <div class="popular-tour-tabs mt-20">
          @foreach ($allCategories as $slug => $data)
            @php
              $btn    = $categoryButtons[$slug] ?? null;
              $btnUrl = !empty($btn['url'])    ? esc_url($btn['url'])    : 'javascript:void(0);';
              $btnLbl = !empty($btn['title'])  ? $btn['title']           : 'VIEW ALL ' . strtoupper($data['name']) . ' TOURS';
              $btnTarget = !empty($btn['target']) ? $btn['target']       : '_self';
            @endphp
            <button
              type="button"
              class="{{ $loop->first ? 'active' : '' }}"
              data-city="{{ esc_attr($slug) }}"
              data-city-link="{{ esc_url($data['link']) }}"
              data-cta-url="{{ $btnUrl }}"
              data-cta-label="{{ esc_attr($btnLbl) }}"
              data-cta-target="{{ esc_attr($btnTarget) }}">
              {{ strtoupper($data['name']) }}
            </button>
          @endforeach
        </div>

        @if (!empty($content->ompt_description))
          <div class="content mt-20">
            <p>{{ $content->ompt_description }}</p>
          </div>
        @endif

        {{-- View all button (desktop) — updates on tab switch via JS --}}
        <div class="btn-custom mt-46 max-1023:hidden">
          <a href="{{ $firstCategoryLink }}"
             class="btn btn-orange popular-tour-cta"
             aria-label="{{ esc_attr($firstCategoryLabel) }}"
             data-city="{{ esc_attr($firstCategory) }}"
             target="{{ esc_attr($firstCategoryTarget) }}"
             role="link">
            {{ strtoupper($firstCategoryLabel) }}
          </a>
        </div>
      </div>

      {{-- Tour cards wrapper — AJAX replaces content on tab switch --}}
      <div class="popular-tour-cards-wrap" data-city="{{ esc_attr($firstCategory) }}">
        <div class="popular-tour-slider swiper-container">
          <div class="popular-tour-cards swiper-wrapper">
          </div>
        </div>
        <div class="popular-tour-progress" aria-hidden="true"></div>
      </div>
    </div>

    {{-- View all button (mobile) — updates on tab switch via JS --}}
    <div class="btn-custom mt-24 hidden max-1023:flex justify-center">
      <a href="{{ $firstCategoryLink }}"
         class="btn btn-orange popular-tour-cta"
         aria-label="{{ esc_attr($firstCategoryLabel) }}"
         data-city="{{ esc_attr($firstCategory) }}"
         target="{{ esc_attr($firstCategoryTarget) }}"
         role="link">
        {{ strtoupper($firstCategoryLabel) }}
      </a>
    </div>
  </div>
</section>
