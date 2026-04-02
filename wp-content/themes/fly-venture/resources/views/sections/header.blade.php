<header class="site-header{{ ((is_single() && !is_singular('tour')) || is_404()) ? ' header-relative' : '' }}" id="site-header">
  <div class="announcement-bar" aria-label="Announcements">
    <div class="flex flex-wrap justify-between items-center px-60 max-1199:px-0">

      {{-- Announcement Slider --}}
      <div class="announcement-slider swiper-container" id="announcement-slider">
        <div class="swiper-wrapper">

          @php
            $announcement_text   = $announcement_text ?? '';
            $announcement_button = (!empty($announcement_button) && is_array($announcement_button)) ? $announcement_button : [];
            $announcement_url    = esc_url($announcement_button['url'] ?? '');
            $announcement_title  = esc_html($announcement_button['title'] ?? '');
          @endphp

          @for ($i = 0; $i < 6; $i++)
            <div class="swiper-slide">
              <p class="announcement-item">
                {!! wp_kses_post($announcement_text) !!}
                @if(!empty($announcement_url) && !empty($announcement_title))
                  <a href="{{ $announcement_url }}" class="btn-white" aria-label="{{ $announcement_title }}" role="link">
                    {{ $announcement_title }}
                  </a>
                @endif
              </p>
            </div>
          @endfor

        </div>
      </div>

      {{-- Right Side: Phone + CTA --}}
      <div class="right-side-social">

        @php
          $header_phone       = $header_phone ?? '';
          $header_phone_label = !empty($header_phone) ? esc_html($header_phone) : '(844) 359-8368';
          $header_phone_href  = !empty($header_phone) ? esc_url('tel:' . $header_phone) : 'tel:(844) 359-8368';

          $book_flight_button = (!empty($book_flight_button) && is_array($book_flight_button)) ? $book_flight_button : [];
          $book_flight_url    = esc_url($book_flight_button['url'] ?? 'javascript:void(0);');
          $book_flight_title  = esc_html($book_flight_button['title'] ?? 'BOOK YOUR FLIGHT');
        @endphp

        <div class="phone">
          <img
            src="{{ get_theme_file_uri('/resources/images/white-call-icon.svg') }}"
            height="12"
            width="12"
            alt="phone icon"
          >
          <a href="{{ $header_phone_href }}" aria-label="{{ $header_phone_label }}" role="link">
            {{ $header_phone_label }}
          </a>
        </div>

        <div class="btn-custom">
          <a href="{{ $book_flight_url }}" class="btn btn-orange" aria-label="{{ $book_flight_title }}" role="link">
            {{ $book_flight_title }}
          </a>
        </div>

      </div>

    </div>
  </div>

  <div class="main-header">
    <div class="container-fluid">
      <div class="header-row">

        <a href="{{ home_url('/') }}" class="site-logo" aria-label="{{ get_bloginfo('name') }}">
          @if(!empty($header_logo) && is_array($header_logo) && !empty($header_logo['url']))
            <img
              src="{{ esc_url($header_logo['url']) }}"
              alt="{{ esc_attr($header_logo['alt'] ?? get_bloginfo('name')) }}"
              class="max-1441:max-w-250 max-1199:max-w-200 h-full w-full object-cover xl:pb-16"
              height="60"
              width="280"
            >
          @else
            <img
              src="{{ get_theme_file_uri('/resources/images/flyventure/logo.png') }}"
              alt="{{ get_bloginfo('name') }}"
              class="max-1441:max-w-250 max-1199:max-w-200 h-full w-full object-cover"
              height="60"
              width="280"
            >
          @endif
        </a>

        <button
          class="menu-toggle"
          id="menu-toggle"
          aria-expanded="false"
          aria-controls="menu-main-menu"
          aria-label="Toggle menu"
        >
          <svg class="menu-toggle-btn" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 15C20.5523 15 21 15.4477 21 16C21 16.5523 20.5523 17 20 17H4C3.44772 17 3 16.5523 3 16C3 15.4477 3.44772 15 4 15H20ZM20 7C20.5523 7 21 7.44772 21 8C21 8.55228 20.5523 9 20 9H4C3.44772 9 3 8.55228 3 8C3 7.44772 3.44772 7 4 7H20Z" fill="white"/>
          </svg>

          <svg class="menu-open-btn" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18.3002 5.71001C18.2077 5.61731 18.0978 5.54376 17.9768 5.49358C17.8559 5.4434 17.7262 5.41757 17.5952 5.41757C17.4643 5.41757 17.3346 5.4434 17.2136 5.49358C17.0926 5.54376 16.9827 5.61731 16.8902 5.71001L12.0002 10.59L7.11022 5.70001C7.01764 5.60743 6.90773 5.53399 6.78677 5.48388C6.6658 5.43378 6.53615 5.40799 6.40522 5.40799C6.27429 5.40799 6.14464 5.43378 6.02368 5.48388C5.90272 5.53399 5.79281 5.60743 5.70022 5.70001C5.60764 5.79259 5.5342 5.9025 5.4841 6.02347C5.43399 6.14443 5.4082 6.27408 5.4082 6.40501C5.4082 6.53594 5.43399 6.66559 5.4841 6.78655C5.5342 6.90752 5.60764 7.01743 5.70022 7.11001L10.5902 12L5.70022 16.89C5.60764 16.9826 5.5342 17.0925 5.4841 17.2135C5.43399 17.3344 5.4082 17.4641 5.4082 17.595C5.4082 17.7259 5.43399 17.8556 5.4841 17.9766C5.5342 18.0975 5.60764 18.2074 5.70022 18.3C5.79281 18.3926 5.90272 18.466 6.02368 18.5161C6.14464 18.5662 6.27429 18.592 6.40522 18.592C6.53615 18.592 6.6658 18.5662 6.78677 18.5161C6.90773 18.466 7.01764 18.3926 7.11022 18.3L12.0002 13.41L16.8902 18.3C16.9828 18.3926 17.0927 18.466 17.2137 18.5161C17.3346 18.5662 17.4643 18.592 17.5952 18.592C17.7262 18.592 17.8558 18.5662 17.9768 18.5161C18.0977 18.466 18.2076 18.3926 18.3002 18.3C18.3928 18.2074 18.4662 18.0975 18.5163 17.9766C18.5665 17.8556 18.5922 17.7259 18.5922 17.595C18.5922 17.4641 18.5665 17.3344 18.5163 17.2135C18.4662 17.0925 18.3928 16.9826 18.3002 16.89L13.4102 12L18.3002 7.11001C18.6802 6.73001 18.6802 6.09001 18.3002 5.71001Z" fill="white"/>
          </svg>


        </button>

        @if (has_nav_menu('primary_navigation'))
          <nav class="main-navigation" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
            {!! wp_nav_menu([
              'theme_location' => 'primary_navigation',
              'container'      => false,
              'menu_class'     => 'menu',
              'items_wrap'     => '<ul id="menu-main-menu" class="%2$s">%3$s</ul>',
              'echo'           => false,
            ]) !!}
          </nav>
        @endif

      </div>
    </div>
  </div>
</header>
