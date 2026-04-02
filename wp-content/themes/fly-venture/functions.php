<?php

use Roots\Acorn\Application;
if ( ! defined( 'ABSPATH' ) ) exit;
// Enable Gutenberg only for blog posts; disable for pages and all CPTs (including tours)
add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) {
    if ($post_type === 'post') {
        return true;
    }
    return false;
}, 10, 2);

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

Application::configure()
    ->withProviders([
        App\Providers\ThemeServiceProvider::class,
    ])
    ->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });

/**
 * Register ACF Theme Options pages for Header and Footer settings.
 */
add_action('acf/init', function () {
    if (! function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => __('Theme Options', 'sage'),
        'menu_title' => __('Theme Options', 'sage'),
        'menu_slug' => 'theme-options',
        'capability' => 'edit_theme_options',
        'redirect' => true,
        'position' => 61,
        'icon_url' => 'dashicons-admin-generic',
    ]);

});


remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_filter('the_content', 'wp_staticize_emoji');
remove_filter('comment_text', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('wp_resource_hints', function($urls, $relation_type) {
    if ($relation_type === 'dns-prefetch') {
        $urls = array_filter($urls, fn($url) => !str_contains($url, 'emoji'));
    }
    return $urls;
}, 10, 2);
function flyventure_render_svg_rating($rating, $unique_id = '') {

    $rating = floatval($rating);
    $starWidth = 19;

    ob_start();
    ?>

    <div class="rating-svg" style="display:flex; gap:8px;">
        <?php for ($i = 0; $i < 5; $i++) : ?>

            <?php
            // ⭐ Calculate per-star fill
            $starFill = max(0, min(1, $rating - $i));
            $fillWidth = $starFill * $starWidth;

            $clipId = 'clip-' . $unique_id . '-' . $i . '-' . uniqid();
            ?>

            <svg width="17" height="17" viewBox="0 0 19 18" xmlns="http://www.w3.org/2000/svg">

                <defs>
                    <!-- pixel-perfect clipping -->
                    <clipPath id="<?php echo esc_attr($clipId); ?>" clipPathUnits="userSpaceOnUse">
                        <rect x="0" y="0" width="<?php echo esc_attr($fillWidth); ?>" height="18"></rect>
                    </clipPath>
                </defs>

                <!-- EMPTY STAR -->
                <path fill="#FFFFFF"
                      d="M18.2794 6.59063C18.2375 6.45776 18.159 6.33941 18.0529 6.24919C17.9467 6.15897 17.8173 6.10054 17.6794 6.08063L12.2594 5.29063L9.82941 0.380625C9.76025 0.264599 9.66214 0.168521 9.5447 0.101799C9.42725 0.0350778 9.29449 0 9.15941 0C9.02434 0 8.89158 0.0350778 8.77413 0.101799C8.65668 0.168521 8.55857 0.264599 8.48941 0.380625L6.05941 5.29063L0.639412 6.08063C0.501538 6.10054 0.372084 6.15897 0.265942 6.24919C0.1598 6.33941 0.0812794 6.45776 0.0394126 6.59063C-0.00528311 6.723 -0.0121144 6.86523 0.0196891 7.00128C0.0514926 7.13732 0.120666 7.26178 0.219412 7.36063L4.15941 11.1806L3.22941 16.5806C3.20584 16.7197 3.22142 16.8625 3.2744 16.9932C3.32738 17.1239 3.41568 17.2373 3.52941 17.3206C3.64333 17.4018 3.77768 17.4495 3.91728 17.4583C4.05688 17.4672 4.19617 17.4368 4.31941 17.3706L9.15941 14.8206L14.0094 17.3706C14.1167 17.4295 14.237 17.4604 14.3594 17.4606C14.5167 17.4582 14.6697 17.4095 14.7994 17.3206C14.9131 17.2373 15.0014 17.1239 15.0544 16.9932C15.1074 16.8625 15.123 16.7197 15.0994 16.5806L14.1594 11.1806L18.0894 7.36063C18.1899 7.26286 18.2609 7.1389 18.2945 7.00279C18.3281 6.86669 18.3229 6.7239 18.2794 6.59063Z"/>

                <!-- FILLED PART -->
                <path fill="#E26216"
                      clip-path="url(#<?php echo esc_attr($clipId); ?>)"
                      d="M18.2794 6.59063C18.2375 6.45776 18.159 6.33941 18.0529 6.24919C17.9467 6.15897 17.8173 6.10054 17.6794 6.08063L12.2594 5.29063L9.82941 0.380625C9.76025 0.264599 9.66214 0.168521 9.5447 0.101799C9.42725 0.0350778 9.29449 0 9.15941 0C9.02434 0 8.89158 0.0350778 8.77413 0.101799C8.65668 0.168521 8.55857 0.264599 8.48941 0.380625L6.05941 5.29063L0.639412 6.08063C0.501538 6.10054 0.372084 6.15897 0.265942 6.24919C0.1598 6.33941 0.0812794 6.45776 0.0394126 6.59063C-0.00528311 6.723 -0.0121144 6.86523 0.0196891 7.00128C0.0514926 7.13732 0.120666 7.26178 0.219412 7.36063L4.15941 11.1806L3.22941 16.5806C3.20584 16.7197 3.22142 16.8625 3.2744 16.9932C3.32738 17.1239 3.41568 17.2373 3.52941 17.3206C3.64333 17.4018 3.77768 17.4495 3.91728 17.4583C4.05688 17.4672 4.19617 17.4368 4.31941 17.3706L9.15941 14.8206L14.0094 17.3706C14.1167 17.4295 14.237 17.4604 14.3594 17.4606C14.5167 17.4582 14.6697 17.4095 14.7994 17.3206C14.9131 17.2373 15.0014 17.1239 15.0544 16.9932C15.1074 16.8625 15.123 16.7197 15.0994 16.5806L14.1594 11.1806L18.0894 7.36063C18.1899 7.26286 18.2609 7.1389 18.2945 7.00279C18.3281 6.86669 18.3229 6.7239 18.2794 6.59063Z"/>

            </svg>

        <?php endfor; ?>
    </div>

    <?php
    return ob_get_clean();
}
/**
 * AJAX: Popular tours filter.
 */
add_action('wp_ajax_flyventure_popular_tours', 'flyventure_ajax_popular_tours');
add_action('wp_ajax_nopriv_flyventure_popular_tours', 'flyventure_ajax_popular_tours');

function flyventure_get_popular_tours_html($category, $selected_ids) {
    $category = sanitize_text_field((string) $category);
    $selected_ids = array_filter(array_map('absint', (array) $selected_ids));

    $args = [
        'post_type' => 'tours',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'no_found_rows' => true,
        'ignore_sticky_posts' => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => true,
    ];

    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'tour_category',
                'field' => 'slug',
                'terms' => $category,
            ],
        ];
    }

    if (!empty($selected_ids)) {
        $args['post__in'] = $selected_ids;
        $args['orderby'] = 'post__in';
        $args['posts_per_page'] = count($selected_ids);
    }

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) {
        foreach ($query->posts as $tour) {
            $html .= flyventure_render_popular_tour_card($tour, $category);
        }
    }

    wp_reset_postdata();

    return $html;
}

function flyventure_ajax_popular_tours() {
    check_ajax_referer('flyventure_popular_tours', 'nonce');

    $category = sanitize_text_field(wp_unslash($_POST['category'] ?? ''));
    $selected = sanitize_text_field(wp_unslash($_POST['selected_tours'] ?? ''));
    $selected_ids = array_filter(array_map('absint', explode(',', $selected)));

    $html = flyventure_get_popular_tours_html($category, $selected_ids);
    wp_send_json_success(['html' => $html]);
}

function flyventure_render_popular_tour_card($tour, $active_category = '') {
    $tour = get_post($tour);
    if (!$tour) {
        return '';
    }

    $terms    = get_the_terms($tour->ID, 'tour_category');
    $tourTags = get_the_terms($tour->ID, 'tour_tag');
    $citySlug = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->slug : '';
    $cityName = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

    if (!empty($active_category) && $citySlug !== $active_category) {
        return '';
    }

//    $priceBlock    = function_exists('get_field') ? (array) get_field('price_block', $tour->ID) : [];
    $priceBlock = function_exists('get_field') ? (array) get_field('price_block', $tour->ID) : [];
    $flightDuration = function_exists('get_field') ? (array) get_field('flight_duration', $tour->ID) : [];
    $bestFor       = function_exists('get_field') ? (string) get_field('best_for', $tour->ID) : '';
    $buttonGroup   = function_exists('get_field') ? (array) get_field('button_group', $tour->ID) : [];
    $reviewBlock   = function_exists('get_field') ? (array) get_field('review_block', $tour->ID) : [];

    // Price block
    $originalPrice           = $priceBlock['regular_price'] ?? '';
    $offerPrice              = $priceBlock['offer_price'] ?? '';
    $tooltip_for_price_tag  = $priceBlock['tooltip_for_price_tag'] ?? '';
    $perPersonText    = $priceBlock['per_person_text']    ?? '';


    // Flight duration
    $durationLabel = $flightDuration['flight_duration_text'] ?? '';
    $duration      = $flightDuration['flight_duration'] ?? '';

    // Buttons
    $bookBtn  = $buttonGroup['book_now_button'] ?? [];
    $bookUrl  = !empty($bookBtn['url']) ? $bookBtn['url'] : '#';
    $learnButtonText = !empty($buttonGroup['learn_more_button'])
        ? (is_array($buttonGroup['learn_more_button'])
            ? implode(', ', $buttonGroup['learn_more_button'])
            : $buttonGroup['learn_more_button'])
        : '';

    // Review block
    $rating = floatval($reviewBlock['select_star_rating'] ?? 4.5);
    $reviewText = $reviewBlock['review_text'] ?? '';

    // Featured image
    $thumbnail = get_the_post_thumbnail_url($tour->ID, 'medium_large') ?: '';

    // --- Tour tag badge ---
    $badgeLabel = '';
    $tour_icon  = '';
    if (!is_wp_error($tourTags) && !empty($tourTags)) {
        $badgeLabel = $tourTags[0]->name;
        $tour_icon  = get_field('tour_tag_icon', 'term_' . $tourTags[0]->term_id);

    }

// --- Price tags from select_price_tag (inside price_block group) ---
    $priceTags = [];

    if (!empty($priceBlock['select_price_tag'])) {
        $selectedPriceTags = $priceBlock['select_price_tag'];

        // ✅ Handle if ACF returns single object instead of array
        if ($selectedPriceTags instanceof WP_Term) {
            $selectedPriceTags = [$selectedPriceTags];
        }

        if (is_array($selectedPriceTags)) {
            foreach ($selectedPriceTags as $term) {
                if (!($term instanceof WP_Term)) {
                    continue;
                }

                $termId      = $term->term_id;
                $tptIconId   = get_term_meta($termId, 'tpt_icon', true);
                $tptColor    = get_term_meta($termId, 'tpt_color', true);
                $textColor   = get_term_meta($termId, 'tpt_text_color', true);
                $tooltipText = get_term_meta($termId, 'tpt_tooltip_text', true);

                $priceTags[] = [
                    'name'         => $term->name,
                    'slug'         => $term->slug,
                    'text_color'   => $textColor,
                    'color'        => !empty($tptColor) ? sanitize_hex_color($tptColor) : '',
                    'tooltip_text' => !empty($tooltipText) ? $tooltipText : '',
                    'icon_url'     => !empty($tptIconId)
                        ? (filter_var($tptIconId, FILTER_VALIDATE_URL)
                            ? esc_url_raw($tptIconId)
                            : wp_get_attachment_image_url(absint($tptIconId), 'full'))
                        : '',
                ];
            }
        }
    }
    ob_start();
    ?>
    <div class="popular-tour-card swiper-slide" data-city="<?php echo esc_attr($citySlug); ?>">
        <div class="popular-tour-card-media">
            <img
                src="<?php echo esc_url($thumbnail); ?>"
                height="250"
                width="375"
                alt="<?php echo esc_attr($tour->post_title); ?>">
            <div class="popular-tour-badge">
                <?php if (!empty($tour_icon['url'])) : ?>
                    <img
                        src="<?php echo esc_url($tour_icon['url']); ?>"
                        height="14"
                        width="14"
                        alt="<?php echo esc_attr($tour_icon['alt']); ?>">
                <?php endif; ?>

                <span><?php echo esc_html($badgeLabel); ?></span>
            </div>
            <span class="popular-tour-pill"><?php echo esc_html($cityName); ?></span>
        </div>

        <div class="popular-tour-card-body">
            <div class="top-content">
                <h4><?php echo esc_html($tour->post_title); ?></h4>
                <div class="popular-tour-meta">
                    <div>
                        <?php if (!empty($originalPrice)) : ?>
                            <small>From <span><?php echo esc_html($originalPrice); ?></span></small>
                        <?php endif; ?>

                          <div class="flex flex-wrap mt-6 items-baseline gap-2">
                        <?php if (!empty($offerPrice)) : ?>
                            <strong>Now <?php echo esc_html($offerPrice); ?></strong>
                        <?php endif;
                        if (!empty($perPersonText)) : ?>
                              <div class="normal-tag">
                                  <span><?php echo esc_html($perPersonText);?></span>
                              </div>
                          <?php endif; ?>
                          </div>

                        <?php
                        ?>
                        <?php if (!empty($priceTags)) : ?>
                                <?php foreach ($priceTags as $tag) : ?>
                                <div class="best-price-tag price-tag-item" style="<?php echo !empty($tag['color']) ? 'background-color:' . esc_attr($tag['color']) . ';' : ''; ?>">

                                        <?php if (!empty($tag['icon_url'])) : ?>
                                            <img
                                                src="<?php echo esc_url($tag['icon_url']); ?>"
                                                height="14"
                                                width="14"
                                                alt="<?php echo esc_attr($tag['name']); ?>">
                                        <?php endif; ?>

                                    <span style="color:<?php echo esc_attr( sanitize_hex_color( $tag['text_color'] ) ); ?>;"><?php echo esc_html($tag['name']); ?></span>

                                  <?php if (!empty($tag['tooltip_text'])): ?>
                                    <div class="price-tag-tooltip-wrap">
                                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.3565 7.3565C5.45217 7.2605 5.5 7.14167 5.5 7V5C5.5 4.85833 5.452 4.73967 5.356 4.644C5.26 4.54833 5.14133 4.50033 5 4.5C4.85867 4.49967 4.74 4.54767 4.644 4.644C4.548 4.74033 4.5 4.859 4.5 5V7C4.5 7.14167 4.548 7.2605 4.644 7.3565C4.74 7.4525 4.85867 7.50033 5 7.5C5.14133 7.49967 5.26017 7.45217 5.3565 7.3565ZM5.3565 3.356C5.45217 3.26033 5.5 3.14167 5.5 3C5.5 2.85833 5.452 2.73967 5.356 2.644C5.26 2.54833 5.14133 2.50033 5 2.5C4.85867 2.49967 4.74 2.54767 4.644 2.644C4.548 2.74033 4.5 2.859 4.5 3C4.5 3.141 4.548 3.25983 4.644 3.3565C4.74 3.45317 4.85867 3.501 5 3.5C5.14133 3.499 5.26017 3.451 5.3565 3.356ZM5 10C4.30833 10 3.65833 9.86867 3.05 9.606C2.44167 9.34333 1.9125 8.98717 1.4625 8.5375C1.0125 8.08783 0.656334 7.55867 0.394001 6.95C0.131667 6.34133 0.000333966 5.69133 6.32911e-07 5C-0.0003327 4.30867 0.131001 3.65867 0.394001 3.05C0.657001 2.44133 1.01317 1.91217 1.4625 1.4625C1.91183 1.01283 2.441 0.656667 3.05 0.394C3.659 0.131333 4.309 0 5 0C5.691 0 6.341 0.131333 6.95 0.394C7.559 0.656667 8.08817 1.01283 8.5375 1.4625C8.98683 1.91217 9.34317 2.44133 9.6065 3.05C9.86983 3.65867 10.001 4.30867 10 5C9.999 5.69133 9.86767 6.34133 9.606 6.95C9.34433 7.55867 8.98817 8.08783 8.5375 8.5375C8.08683 8.98717 7.55767 9.3435 6.95 9.6065C6.34233 9.8695 5.69233 10.0007 5 10ZM5 9C6.11667 9 7.0625 8.6125 7.8375 7.8375C8.6125 7.0625 9 6.11667 9 5C9 3.88333 8.6125 2.9375 7.8375 2.1625C7.0625 1.3875 6.11667 1 5 1C3.88333 1 2.9375 1.3875 2.1625 2.1625C1.3875 2.9375 1 3.88333 1 5C1 6.11667 1.3875 7.0625 2.1625 7.8375C2.9375 8.6125 3.88333 9 5 9Z"
                                                  fill="<?php echo esc_attr( sanitize_hex_color( $tag['text_color'] ) ); ?>"/>
                                        </svg>
                                        <div class="price-tag-tooltip">
                                            <?php echo esc_html($tag['tooltip_text']); ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                </div>
                                <?php endforeach; ?>

                        <?php endif; ?>

                    </div>

                    <div>
                        <small><strong><?php echo esc_html($durationLabel); ?></strong></small>
                        <div class="time mt-6 inline-flex gap-6 items-center">
                            <img src="<?php echo esc_url(get_theme_file_uri('/resources/images/blue-clock.svg')); ?>" height="18" width="18" alt="clock-svg">
                            <strong><?php echo esc_html($duration); ?> min</strong>
                        </div>
                    </div>
                </div>

                <p><?php echo wp_kses_post($tour->post_content); ?></p>

                <?php if (!empty($bestFor)) : ?>
                    <p><strong>Best for: </strong><?php echo esc_html($bestFor); ?></p>
                <?php endif; ?>
            </div>

            <div class="bottom-content">
                <div class="popular-tour-btns">
                    <a href="<?php echo esc_url($bookUrl); ?>" class="btn btn-orange" aria-label="Book now"><?php echo esc_html($bookBtn['title'] ?? ''); ?></a>
                    <a href="<?php echo get_permalink( $tour->ID ); ?>" class="btn btn-b-white" aria-label="Learn more"><?php echo $learnButtonText; ?></a>
                </div>

                <div class="popular-tour-rating">
                    <?php echo flyventure_render_svg_rating($rating, $tour->ID); ?>
                    <?php echo wp_strip_all_tags($reviewText); ?>
                </div>

            </div>
        </div>
    </div>
    <?php

    return ob_get_clean();
}











add_action( 'wp_ajax_flyventure_load_more_tours',        'flyventure_ajax_load_more_tours' );
add_action( 'wp_ajax_nopriv_flyventure_load_more_tours', 'flyventure_ajax_load_more_tours' );

function flyventure_ajax_load_more_tours(): void {
    check_ajax_referer( 'flyventure_load_more_tours', 'nonce' );

    $offset   = absint( $_POST['offset']   ?? 0 );
    $per_page = absint( $_POST['per_page'] ?? 6 );
    $tour_category = sanitize_key( wp_unslash( $_POST['category'] ?? '' ) );

    // ✅ Build args array first, then apply tax_query, then run WP_Query
    $query_args = [
        'post_type'              => 'tours',
        'post_status'            => 'publish',
        'posts_per_page'         => $per_page,
        'offset'                 => $offset,
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => true,
    ];

    // ✅ tax_query added to args BEFORE WP_Query runs
    if ( ! empty( $tour_category ) ) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => 'tour_category',
                'field'    => 'slug',
                'terms'    => $tour_category,
            ],
        ];
    }
    $query = new WP_Query( $query_args );

    if ( ! $query->have_posts() ) {
        wp_send_json_success( [ 'html' => '', 'has_more' => false ] );
    }

    $html = '';
    foreach ( $query->posts as $tour ) {
        $html .= flyventure_render_tampa_tour_card( $tour );
    }

    wp_reset_postdata();

    wp_send_json_success( [ 'html' => $html, 'has_more' => false ] );
}





function flyventure_render_tampa_tour_card( $tour ): string {
    $tour = get_post( $tour );
    if ( ! $tour ) {
        return '';
    }

    $terms    = get_the_terms( $tour->ID, 'tour_category' );
    $tourTags = get_the_terms( $tour->ID, 'tour_tag' );

    $citySlug = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->slug : '';
    $cityName = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';

    $thumbnail   = get_the_post_thumbnail_url( $tour->ID, 'medium_large' ) ?: '';
    $priceBlock  = function_exists( 'get_field' ) ? (array) get_field( 'price_block',     $tour->ID ) : [];
    $flightDur   = function_exists( 'get_field' ) ? (array) get_field( 'flight_duration',  $tour->ID ) : [];
    $bestFor     = function_exists( 'get_field' ) ? (string) get_field( 'best_for',        $tour->ID ) : '';
    $buttonGroup = function_exists( 'get_field' ) ? (array) get_field( 'button_group',     $tour->ID ) : [];
    $reviewBlock = function_exists( 'get_field' ) ? (array) get_field( 'review_block',     $tour->ID ) : [];



    $priceBlock = is_array( $priceBlock ) ? $priceBlock : [];
    $tooltip_for_price_tag  = $priceBlock['tooltip_for_price_tag'] ?? '';
    $originalPrice = $priceBlock['regular_price'] ?? '';
    $offerPrice    = $priceBlock['offer_price']    ?? '';
    $perPersonText    = $priceBlock['per_person_text']    ?? '';
    $durationLabel = $flightDur['flight_duration_text'] ?? 'Flight duration';
    $duration      = $flightDur['flight_duration']      ?? '';
    $bookUrl  = ! empty( $buttonGroup['book_now_button']['url'] )   ? $buttonGroup['book_now_button']['url']   : 'javascript:void(0);';

    $bookButtonText       = ! empty( $buttonGroup['book_now_button']['title'] )   ? $buttonGroup['book_now_button']['title']   : 'BOOK NOW';

    $learnButtonText = !empty($buttonGroup['learn_more_button'])
        ? (is_array($buttonGroup['learn_more_button'])
            ? implode(', ', $buttonGroup['learn_more_button'])
            : $buttonGroup['learn_more_button'])
        : '';

    $rating = floatval($reviewBlock['select_star_rating'] ?? 4.5);
    $reviewText = $reviewBlock['review_text'] ?? '';

    // ── Badge ─────────────────────────────────────────────────────
    $badgeLabel = '';
    $tour_icon  = '';
    if ( ! is_wp_error( $tourTags ) && ! empty( $tourTags ) ) {
        $badgeLabel = $tourTags[0]->name;
        $tour_icon  = function_exists( 'get_field' )
            ? (string) get_field( 'tour_tag_icon', 'term_' . $tourTags[0]->term_id )
            : '';
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

    // ── Asset URIs ────────────────────────────────────────────────
    $star_uri        = get_theme_file_uri( '/resources/images/star.svg' );
    $unfill_star_uri = get_theme_file_uri( '/resources/images/unfill-star.svg' );
    $clock_uri       = get_theme_file_uri( '/resources/images/blue-clock.svg' );

    ob_start();
    ?>
    <div class="popular-tour-card" data-city="<?php echo esc_attr( $citySlug ); ?>">
        <div class="popular-tour-card-media">
            <?php if ( ! empty( $thumbnail ) ) : ?>
                <img src="<?php echo esc_url( $thumbnail ); ?>" height="250" width="375" alt="<?php echo esc_attr( $tour->post_title ); ?>">
            <?php endif; ?>
            <?php if ( ! empty( $badgeLabel ) ) : ?>
                <div class="popular-tour-badge">
                    <?php if (!empty($tour_icon['url'])) : ?>
                        <img
                            src="<?php echo esc_url($tour_icon['url']); ?>"
                            height="14"
                            width="14"
                            alt="<?php echo esc_attr($tour_icon['alt']); ?>">
                    <?php endif; ?>
                    <span><?php echo esc_html( $badgeLabel ); ?></span>
                </div>
            <?php endif; ?>
            <?php if ( ! empty( $cityName ) ) : ?>
                <span class="popular-tour-pill"><?php echo esc_html( $cityName ); ?></span>
            <?php endif; ?>
        </div>

        <div class="popular-tour-card-body">
            <div class="top-content">
                <h4><?php echo esc_html( $tour->post_title ); ?></h4>

                <div class="popular-tour-meta">

                    <div>
                        <?php if ( ! empty( $originalPrice ) ) : ?>
                            <small>From <span><?php echo esc_html( $originalPrice ); ?></span></small>
                        <?php endif; ?>

                        <div class="flex items-baseline gap-2">
                        <?php if ( ! empty( $offerPrice ) ) : ?>
                            <strong>Now <?php echo esc_html( $offerPrice ); ?></strong>
                        <?php endif; ?>

                        <?php if ( ! empty( $perPersonText ) ): ?>
                        <div class="normal-tag">
                            <span><?php echo esc_html($perPersonText) ?></span>
                        </div>
                         <?php endif ?>
                        </div>

                        <?php if ( ! empty( $priceTags ) ) : ?>
                            <?php foreach ( $priceTags as $tag ) : ?>
                                <div class="best-price-tag price-tag-item"
                                     style="<?php echo ! empty( $tag['color'] ) ? 'background-color:' . esc_attr( $tag['color'] ) . ';' : ''; ?>">

                                    <?php if ( ! empty( $tag['icon_url'] ) ) : ?>
                                        <img src="<?php echo esc_url( $tag['icon_url'] ); ?>" height="14" width="14" alt="<?php echo esc_attr( $tag['name'] ); ?>">
                                    <?php endif; ?>

                                    <span style="color:<?php echo esc_attr( $tag['text_color'] ); ?>;">
                                        <?php echo esc_html( $tag['name'] ); ?>
                                    </span>


                                    <?php if (!empty($tag['tooltip_text'])): ?>
                                        <div class="price-tag-tooltip-wrap">
                                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.3565 7.3565C5.45217 7.2605 5.5 7.14167 5.5 7V5C5.5 4.85833 5.452 4.73967 5.356 4.644C5.26 4.54833 5.14133 4.50033 5 4.5C4.85867 4.49967 4.74 4.54767 4.644 4.644C4.548 4.74033 4.5 4.859 4.5 5V7C4.5 7.14167 4.548 7.2605 4.644 7.3565C4.74 7.4525 4.85867 7.50033 5 7.5C5.14133 7.49967 5.26017 7.45217 5.3565 7.3565ZM5.3565 3.356C5.45217 3.26033 5.5 3.14167 5.5 3C5.5 2.85833 5.452 2.73967 5.356 2.644C5.26 2.54833 5.14133 2.50033 5 2.5C4.85867 2.49967 4.74 2.54767 4.644 2.644C4.548 2.74033 4.5 2.859 4.5 3C4.5 3.141 4.548 3.25983 4.644 3.3565C4.74 3.45317 4.85867 3.501 5 3.5C5.14133 3.499 5.26017 3.451 5.3565 3.356ZM5 10C4.30833 10 3.65833 9.86867 3.05 9.606C2.44167 9.34333 1.9125 8.98717 1.4625 8.5375C1.0125 8.08783 0.656334 7.55867 0.394001 6.95C0.131667 6.34133 0.000333966 5.69133 6.32911e-07 5C-0.0003327 4.30867 0.131001 3.65867 0.394001 3.05C0.657001 2.44133 1.01317 1.91217 1.4625 1.4625C1.91183 1.01283 2.441 0.656667 3.05 0.394C3.659 0.131333 4.309 0 5 0C5.691 0 6.341 0.131333 6.95 0.394C7.559 0.656667 8.08817 1.01283 8.5375 1.4625C8.98683 1.91217 9.34317 2.44133 9.6065 3.05C9.86983 3.65867 10.001 4.30867 10 5C9.999 5.69133 9.86767 6.34133 9.606 6.95C9.34433 7.55867 8.98817 8.08783 8.5375 8.5375C8.08683 8.98717 7.55767 9.3435 6.95 9.6065C6.34233 9.8695 5.69233 10.0007 5 10ZM5 9C6.11667 9 7.0625 8.6125 7.8375 7.8375C8.6125 7.0625 9 6.11667 9 5C9 3.88333 8.6125 2.9375 7.8375 2.1625C7.0625 1.3875 6.11667 1 5 1C3.88333 1 2.9375 1.3875 2.1625 2.1625C1.3875 2.9375 1 3.88333 1 5C1 6.11667 1.3875 7.0625 2.1625 7.8375C2.9375 8.6125 3.88333 9 5 9Z"
                                                      fill="<?php echo esc_attr( $tag['text_color'] ); ?>"/>
                                            </svg>
                                            <div class="price-tag-tooltip">
                                                <?php echo esc_html($tag['tooltip_text']); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>


                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div>
                        <small><strong><?php echo esc_html( $durationLabel ); ?></strong></small>
                        <?php if ( ! empty( $duration ) ) : ?>
                            <div class="time mt-6 inline-flex gap-6 items-center">
                                <img src="<?php echo esc_url( $clock_uri ); ?>" height="18" width="18" alt="clock">
                                <strong><?php echo esc_html( $duration ); ?> min</strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ( ! empty( $tour->post_content ) ) : ?>
                    <p><?php echo wp_kses_post( $tour->post_content ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $bestFor ) ) : ?>
                    <p><strong>Best for:</strong> <?php echo esc_html( $bestFor ); ?></p>
                <?php endif; ?>
            </div>

            <div class="bottom-content">
                <div class="popular-tour-btns">
                    <a href="<?php echo esc_url( $bookUrl ); ?>"  class="btn btn-orange"  aria-label="Book now"><?php echo  $bookButtonText; ?></a>
                    <a href="<?php echo esc_url(get_permalink( $tour->ID )); ?>" class="btn btn-b-white" aria-label="Learn more"><?php echo $learnButtonText;?></a>
                </div>

                <div class="popular-tour-rating">
                    <?php echo flyventure_render_svg_rating($rating, $tour->ID); ?>
                    <?php echo wp_strip_all_tags($reviewText); ?>
                </div>

            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}


// ── Blog Listing AJAX ──────────────────────────────────────────────────────

add_action( 'wp_ajax_flyventure_blog_listing',        'flyventure_ajax_blog_listing' );
add_action( 'wp_ajax_nopriv_flyventure_blog_listing', 'flyventure_ajax_blog_listing' );

function flyventure_ajax_blog_listing(): void {
    check_ajax_referer( 'flyventure_blog_listing', 'nonce' );

    $paged    = max( 1, absint( $_POST['paged'] ?? 1 ) );
    $per_page = 9;
    $search   = sanitize_text_field( wp_unslash( $_POST['search'] ?? '' ) );
    $category = absint( $_POST['category'] ?? 0 );

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => false,
    ];

    if ( ! empty( $search ) ) {
        $args['s'] = $search;
    }

    if ( ! empty( $category ) ) {
        $args['cat'] = $category;
    }

    $query = new WP_Query( $args );

    $posts = [];
    if ( $query->have_posts() ) {
        foreach ( $query->posts as $post ) {
            $post_id    = $post->ID;
            $thumb_id   = get_post_thumbnail_id( $post_id );
            $categories = get_the_category( $post_id );
            $cat        = ! empty( $categories ) ? $categories[0] : null;

            $posts[] = [
                'id'          => $post_id,
                'title'       => get_the_title( $post_id ),
                'permalink'   => get_permalink( $post_id ),
                'author'      => get_the_author_meta( 'display_name', $post->post_author ),
                'author_url'  => get_author_posts_url( $post->post_author ),
                'date'        => get_the_date( '', $post_id ),
                'date_time'   => get_the_date( 'c', $post_id ),
                'category'    => $cat ? [
                    'name' => $cat->name,
                    'url'  => get_category_link( $cat->term_id ),
                ] : null,
                'thumbnail'   => $thumb_id ? [
                    'url' => wp_get_attachment_image_url( $thumb_id, 'large' ) ?: '',
                    'alt' => get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $post_id ),
                ] : null,
                'excerpt'     => get_the_excerpt( $post_id ),
            ];
        }
    }

    wp_reset_postdata();

    wp_send_json_success( [
        'posts'         => $posts,
        'max_num_pages' => (int) $query->max_num_pages,
        'paged'         => $paged,
        'found_posts'   => (int) $query->found_posts,
    ] );
}
