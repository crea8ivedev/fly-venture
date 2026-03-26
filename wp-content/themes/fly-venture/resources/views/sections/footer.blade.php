{{-- Offer Popup --}}
@php
  $popup_image          = $op_popup_image ?? [];
  $popup_content_1      = $op_content_1 ?? '';
  $popup_content_2      = $op_content_2 ?? '';
  $coupon_block         = $coupan_block ?? [];
  $coupon_block_title   = $coupon_block['coupan_block_title'] ?? '';
  $coupon_code          = $coupon_block['coupon_code'] ?? '';
  $popup_button         = $book_your_flight_button ?? [];
  $popup_button_url     = $popup_button['url'] ?? '';
  $popup_button_title   = $popup_button['title'] ?? '';
  $popup_button_target  = $popup_button['target'] ?? '_self';


  $show_popup = !empty($popup_image['url'])
    || !empty($popup_content_1)
    || !empty($popup_content_2)
    || !empty($coupon_code)
    || !empty($popup_button_url);
@endphp

<footer class="site-footer">
  <div class="container-fluid">
    <div class="footer-top">

      {{-- About Section --}}
      <div class="footer-col">
        <div class="title title-white">
          <h4>{{ $footer_about_title ?? '' }}</h4>
        </div>

        <div class="content mt-25">
          <p>{{ $footer_about_description ?? '' }}</p>
        </div>

        {{-- Social Icons --}}
        <ul class="footer-social">
          @if(!empty($social_logos) && is_array($social_logos))
            @foreach($social_logos as $social)
              @php
                $link = $social['social_media_link'] ?? '';
                $img  = $social['social_media_image'] ?? [];
              @endphp
              @if(!empty($link) && !empty($img['url']))
                <li>
                  <a href="{{ esc_url($link) }}"
                     aria-label="{{ esc_attr($img['title'] ?? '') }}"
                     target="_blank">

                    <img
                      src="{{ esc_url($img['url']) }}"
                      alt="{{ esc_attr($img['title'] ?? '') }}"
                      width="{{ esc_attr($img['width'] ?? '') }}"
                      height="{{ esc_attr($img['height'] ?? '') }}"
                    >

                  </a>
                </li>
              @endif
            @endforeach
          @endif
        </ul>
      </div>


      {{-- Contact Section --}}
      <div class="footer-col ">

        <div class="title title-white">
          <h4>{{ $footer_contact_detail_title ?? '' }}</h4>
        </div>

        @if(!empty($footer_contact_details) && is_array($footer_contact_details))
          <ul class="footer-contact-list">

            {{-- Phone --}}
            @if(!empty($footer_contact_details['phone_number']))
              <li>
                <div class="flex items-center gap-8">
                  @if(!empty($footer_contact_details['phone_icon']['url']))
                    <img
                      src="{{ esc_url($footer_contact_details['phone_icon']['url']) }}"
                      class="w-full mt-2 h-full object-cover max-w-24"
                      width="{{ esc_attr($footer_contact_details['phone_icon']['width'] ?? '26') }}"
                      height="{{ esc_attr($footer_contact_details['phone_icon']['height'] ?? '26') }}"
                      alt="{{ esc_attr($footer_contact_details['phone_icon']['alt'] ?? 'phone-icon') }}"
                    >
                  @endif
                  <strong>{{ $footer_contact_details['phone_label'] ?? 'Phone' }}</strong>
                </div>
                <a href="tel:{{ esc_attr(preg_replace('/\s+/', '', $footer_contact_details['phone_number'])) }}">
                  {{ $footer_contact_details['phone_number'] }}
                </a>
              </li>
            @endif

            {{-- Email --}}
            @if(!empty($footer_contact_details['email']))
              <li>
                <div class="flex items-center gap-8">
                  @if(!empty($footer_contact_details['email_icon']['url']))
                    <img
                      src="{{ esc_url($footer_contact_details['email_icon']['url']) }}"
                      class="w-full mt-2 h-full object-cover max-w-24"
                      width="{{ esc_attr($footer_contact_details['email_icon']['width'] ?? '26') }}"
                      height="{{ esc_attr($footer_contact_details['email_icon']['height'] ?? '26') }}"
                      alt="{{ esc_attr($footer_contact_details['email_icon']['alt'] ?? 'mail-icon') }}"
                    >
                  @endif
                  <strong>{{ $footer_contact_details['email_label'] ?? 'Email' }}</strong>
                </div>
                <a href="mailto:{{ esc_attr($footer_contact_details['email']) }}">
                  {{ $footer_contact_details['email'] }}
                </a>
              </li>
            @endif

            {{-- Address --}}
            @if(!empty($footer_contact_details['address']))
              @php
                $address = $footer_contact_details['address'];

                $address_icon_id = $footer_contact_details['address_icon_image']['id'] ?? '';
                $address_icon_url = !empty($address_icon_id) ? wp_get_attachment_image_url($address_icon_id, 'full') : '';
                $address_icon_meta = !empty($address_icon_id) ? wp_get_attachment_metadata($address_icon_id) : [];


              @endphp
              <li>
                <div class="flex items-center gap-8">
                  @if(!empty($address_icon_url))
                    <img
                      src="{{ esc_url($address_icon_url) }}"
                      class="w-full mt-2 h-full object-cover max-w-24"
                      width="{{ esc_attr($address_icon_meta['width'] ?? '26') }}"
                      height="{{ esc_attr($address_icon_meta['height'] ?? '26') }}"
                      alt="{{ esc_attr($footer_contact_details['address_label'] ?? 'address-icon') }}"
                    >
                  @endif
                  <strong>{{ $footer_contact_details['address_label'] ?? 'Address' }}</strong>
                </div>

                @if(!empty($address))
                 <span> {!! $address !!}</span>
                @endif
              </li>
            @endif

          </ul>
        @endif

      </div>

      {{-- Quick Links --}}
      <div class="footer-col ">

        <div class="title title-white">
          <h4>{{ $footer_quick_link_title ?? '' }}</h4>
        </div>

        @if (has_nav_menu('footer_navigation'))
          {!! wp_nav_menu([
            'theme_location' => 'footer_navigation',
            'container' => false,
            'menu_class' => 'footer-links',
            'echo' => false,
          ]) !!}
        @endif

      </div>


      {{-- Footer Logo --}}
      <div class="footer-col footer-logo-col  max-1023:w-full max-1023:justify-center">

        <a href="{{ home_url('/') }}" class="footer-logo max-1023:!hidden" aria-label="{{ get_bloginfo('name') }}">
          @if(!empty($footer_logo) && is_array($footer_logo) && !empty($footer_logo['url']))
            <img
              src="{{ esc_url($footer_logo['url']) }}"
              width="{{ esc_attr($footer_logo['width'] ?? '') }}"
              height="{{ esc_attr($footer_logo['height'] ?? '') }}"
              alt="{{ esc_attr(get_bloginfo('name')) }}"
            >
          @endif
        </a>

        <a href="{{ home_url('/') }}" class="mobile-footer-logo !hidden max-1023:!block" aria-label="{{ get_bloginfo('name') }}">
          @if(!empty($footer_logo_mobile) && is_array($footer_logo_mobile) && !empty($footer_logo_mobile['url']))
            <img
              src="{{ esc_url($footer_logo_mobile['url']) }}"
              width="{{ esc_attr($footer_logo_mobile['width'] ?? '') }}"
              height="{{ esc_attr($footer_logo_mobile['height'] ?? '') }}"
              alt="{{ esc_attr(get_bloginfo('name')) }}"
            >
          @endif
        </a>


      </div>

    </div>


    {{-- Bottom Copyright --}}
    <div class="footer-bottom">
      @php
        $copyright = $footer_copyright_text ?? '';

        $year = date('Y');

        $site_name = get_bloginfo('name');
        $site_url = home_url('/');

        $site_link = '<a href="'.esc_url($site_url).'">'.esc_html($site_name).'</a>';

        $copyright = str_replace(
          ['[year]', '[site-name]'],
          [$year, $site_link],
          $copyright
        );
      @endphp

      @if(!empty($copyright))
        {!! wp_kses_post($copyright) !!}
      @endif
    </div>

  </div>


    <div class="fv-offer-popup" id="fv-offer-popup" aria-hidden="true">
      <div class="fv-offer-popup__overlay" data-popup-close></div>
      <div class="fv-card-container">
        <div class="fv-offer-popup__card"
             role="dialog"
             aria-modal="true"
             aria-labelledby="fv-offer-popup-title">

          <button type="button"
                  class="fv-offer-popup__close"
                  aria-label="{{ esc_attr__('Close popup', 'textdomain') }}"
                  data-popup-close>
            &times;
          </button>

          {{-- Popup Image --}}
          @if(!empty($popup_image['url']))
            <div class="fv-offer-popup__media">
              <img
                src="{{ esc_url($popup_image['url']) }}"
                alt="{{ esc_attr($popup_image['alt'] ?? '') }}"
                width="{{ esc_attr($popup_image['width'] ?? '') }}"
                height="{{ esc_attr($popup_image['height'] ?? '') }}"
                loading="lazy"
              >
            </div>
          @endif

          <div class="fv-offer-popup__content">

            {{-- Lead Text --}}
            @if(!empty($popup_content_1))
              <p class="fv-offer-popup__lead" id="fv-offer-popup-title">
                {{ wp_strip_all_tags($popup_content_1) }}
              </p>
            @endif

            {{-- Coupon Block --}}
            @if(!empty($coupon_block_title) || !empty($coupon_code))
              <div class="code-tikit">
                <div class="fv-offer-popup__code-wrap">
                  @if(!empty($coupon_block_title))
                    <small>{{ esc_html($coupon_block_title) }}</small>
                  @endif
                  @if(!empty($coupon_code))
                    <strong>{{ esc_html($coupon_code) }}</strong>
                  @endif
                </div>
              </div>
            @endif

            {{-- Note Text --}}
            @if(!empty($popup_content_2))
              <p class="fv-offer-popup__note">
                {{ wp_strip_all_tags($popup_content_2) }}
              </p>
            @endif

            {{-- CTA Button --}}
            @if(!empty($popup_button_url) && !empty($popup_button_title))
              <a href="{{ esc_url($popup_button_url) }}"
                 class="btn btn-orange"
                 aria-label="{{ esc_attr($popup_button_title) }}"
                 target="{{ in_array($popup_button_target, ['_blank', '_self', '_parent', '_top']) ? esc_attr($popup_button_target) : '_self' }}"
                 @if($popup_button_target === '_blank') rel="noopener noreferrer" @endif>
                {{ esc_html($popup_button_title) }}
              </a>
            @endif

          </div>
        </div>
      </div>
    </div>

{{--  <div class="fv-offer-popup" id="fv-offer-popup" aria-hidden="true">--}}
{{--    <div class="fv-offer-popup__overlay" data-popup-close></div>--}}
{{--    <div class="fv-card-container">--}}
{{--      <div class="fv-offer-popup__card" role="dialog"--}}
{{--           aria-modal="true"--}}
{{--           aria-labelledby="fv-offer-popup-title">--}}
{{--        <button type="button" class="fv-offer-popup__close"--}}
{{--                aria-label="Close popup" data-popup-close>--}}
{{--          &times;--}}
{{--        </button>--}}

{{--        <div class="fv-offer-popup__media">--}}
{{--          <img src="../images/popup-img.png"--}}
{{--               alt="Passenger in helicopter" loading="lazy">--}}
{{--        </div>--}}

{{--        <div class="fv-offer-popup__content">--}}
{{--          <p class="fv-offer-popup__lead"--}}
{{--             id="fv-offer-popup-title">Wait! Before you go-enjoy--}}
{{--            an extra 10% OFF your first flight.</p>--}}
{{--          <div class="code-tikit">--}}
{{--            <div class="fv-offer-popup__code-wrap">--}}
{{--              <small>Use code</small>--}}
{{--              <strong>FV10</strong>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <p class="fv-offer-popup__note">at checkout and lock in--}}
{{--            your discount today.</p>--}}

{{--          <a href="javascript:void(0);"--}}
{{--             class="btn btn-orange"--}}
{{--             aria-label="book your flight"--}}
{{--             role="link">--}}
{{--            book your flight--}}
{{--          </a>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}
{{--  </div>--}}

</footer>
