<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class PageContentComposer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = ['partials.content-page-content'];

    /**
     * Data passed to Blade view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'pageContentData' => $this->pageContentData(),
        ];
    }

    /**
     * Returns values from ACF group: page-content.
     *
     * @return array
     */
    public function pageContentData()
    {
        return [
            'page_content_data' => $this->buildPageContentData(),
        ];
    }

    /**
     * Flexible content parser for page_content.
     *
     * @return array
     */
    public function buildPageContentData()
    {
        $data = [];
        $flexible_content = get_field('page_content');

        if (!empty($flexible_content) && is_array($flexible_content)) {
            foreach ($flexible_content as $content) {
                if (!is_array($content)) {
                    continue;
                }

                if ('home_banner' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'desktop_media_type' => $content['desktop_media_type'] ?? null,
                        'desktop_background_image' => $content['desktop_background_image'] ?? null,
                        'desktop_background_video' => $content['desktop_background_video'] ?? null,
                        'mobile_media_type' => $content['mobile_media_type'] ?? null,
                        'mobile_background_image' => $content['mobile_background_image'] ?? null,
                        'mobile_background_video' => $content['mobile_background_video'] ?? null,
                        'select_rating' => $content['select_rating'] ?? null,
                        'rating_text' => $content['rating_text'] ?? null,
                        'banner_heading' => $content['banner_heading'] ?? null,
                        'banner_short_description' => $content['banner_short_description'] ?? null,
                        'book_your_flight_button' => $content['book_your_flight_button'] ?? null,
                        'view_more_button' => $content['view_more_button'] ?? null,
                        'home_counter_repeater' => $content['home_counter_repeater'] ?? null,
                        'tours_starting_button' => $content['tours_starting_button'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,

                        //Flight Offer Popup
                        'fo_title' => $content['fo_title'] ?? null,
                        'fo_price' => $content['fo_price'] ?? null,
                        'fo_price_suffix' => $content['fo_price_suffix'] ?? null,
                        'fo_features_list' => $content['fo_features_list'] ?? null,
                    ];
                    continue;
                }
                if ('logo_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'layout_bg_color' => $content['layout_bg_color'] ?? null,
                        'logos' => $content['logos'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }
                if ('customize_your_experience_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'layout_bg_color' => $content['layout_bg_color'] ?? null,
                        'icon' => $content['icon'] ?? null,
                        'cye_title' => $content['cye_title'] ?? null,
                        'cye_short_desciption' => $content['cye_short_desciption'] ?? null,
                        'cye_experience' => $content['cye_experience'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }
                if ('why_choose_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'wc_icon' => $content['wc_icon'] ?? null,
                        'background_color' => $content['background_color'] ?? null,
                        'wc_title' => $content['wc_title'] ?? null,
                        'wc_desciption' => $content['wc_desciption'] ?? null,
                        'wc_features' => $content['wc_features'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }
                if ('review_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'rs_icon' => $content['rs_icon'] ?? null,
                        'rs_title' => $content['rs_title'] ?? null,
                        'rs_desciption' => $content['rs_desciption'] ?? null,
                        'review_button' => $content['review_button'] ?? null, // (Group)
                        'rs_short_code' => $content['rs_short_code'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }
                if ('faq_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'background_color' => $content['background_color'] ?? null,
                        'fs_icon' => $content['fs_icon'] ?? null,
                        'fs_title' => $content['fs_title'] ?? null,
                        'fs_desciption' => $content['fs_desciption'] ?? null,
                        'faqs' => $content['faqs'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('two_image_with_content' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'iwc_image_1' => $content['iwc_image_1'] ?? null,
                        'iwc_image_2' => $content['iwc_image_2'] ?? null,
                        'iwc_icon' => $content['iwc_icon'] ?? null,
                        'iwc_title' => $content['iwc_title'] ?? null,
                        'iwc_sub_title' => $content['iwc_sub_title'] ?? null,
                        'iwc_desciption' => $content['iwc_desciption'] ?? null,
                        'iwc_btn' => $content['iwc_btn'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }
                if ('cta_bg_image_with_content' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'cta_iwc_bg_image' => $content['cta_iwc_bg_image'] ?? null,
                        'icon_image' => $content['icon_image'] ?? null,
                        'cta_iwc_title' => $content['cta_iwc_title'] ?? null,
                        'cta_iwc_short_desciption' => $content['cta_iwc_short_desciption'] ?? null,
                        'cta_iwc_btn' => $content['cta_iwc_btn'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }
                if ('how_it_works' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout'        => $content['acf_fc_layout'] ?? '',
                        'works_icon'    => $content['works_icon'] ?? null,
                        'title'         => $content['title'] ?? null,
                        'sub_title'     => $content['sub_title'] ?? null,
                        'add_steps'     => $content['add_steps'] ?? null,

                        'id' => $content['id'],
                        'extra_class' => $content['extra_class'],
                        'hide_section' => $content['hide_section'],
                    ];
                    continue;
                }
                if ('highlights' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout'             => $content['acf_fc_layout'] ?? '',
                        'highlights_icon'    => $content['highlights_icon'] ?? null,
                        'title'              => $content['title'] ?? null,
                        'sub_title'          => $content['sub_title'] ?? null,
                        'add_highlights'     => $content['add_highlights'] ?? null,

                        'id' => $content['id'],
                        'extra_class' => $content['extra_class'],
                        'hide_section' => $content['hide_section'],
                    ];
                    continue;
                }
                if ('gender_reveal_package' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout'         => $content['acf_fc_layout'] ?? '',
                        'adventure_icon' => $content['adventure_icon'] ?? null,
                        'title'          => $content['title'] ?? null,
                        'sub_title'      => $content['sub_title'] ?? null,
                        'button'         => $content['button'] ?? null,
                        'counties'       => $content['counties'] ?? null,
                        'id'             => $content['id'] ?? null,
                        'class'          => $content['class'] ?? null,
                        'hide_section'   => $content['hide_section'] ?? null,
                        'svg_paths'      => $this->getCountySvgPaths(),
                        'svg_abbrs'      => $this->getCountySvgAbbrs(),
                    ];
                    continue;
                }
                if ('our_most_popular_tours_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'background_color' => $content['background_color'] ?? null,
                        'ompt_icon' => $content['ompt_icon'] ?? null,
                        'ompt_title' => $content['ompt_title'] ?? null,
                        'best_for' => $content['best_for'] ?? null,
                        'ompt_description' => $content['ompt_description'] ?? null,
                        'view_all_sarasota_tours_button' => $content['view_all_sarasota_tours_button'] ?? null,
                        'view_all_tampa_tours_button' => $content['view_all_tampa_tours_button'] ?? null,
                        'view_all_st_pete_tours_button' => $content['view_all_st_pete_tours_button'] ?? null,
                        'select_tours' => $content['select_tours'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }
                if ('image_with_content' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'background_color' => $content['background_color'] ?? null,
                        'image_position' => $content['image_position'] ?? null,
                        'iwc_image' => $content['iwc_image'] ?? null,
                        'iwc_title' => $content['iwc_title'] ?? null,
                        'iwc_description' => $content['iwc_description'] ?? null,
                        'iwc_button' => $content['iwc_button'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }
                if ('what_you_will_see_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'background_color' => $content['wyws_background_color'] ?? null,
                        'wyws_icon' => $content['wyws_icon'] ?? null,
                        'wyws_title' => $content['wyws_title'] ?? null,
                        'wyws_description' => $content['wyws_description'] ?? null,
                        'wyws_locations' => $content['wyws_locations'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }
                if ('tours_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'background_color' => $content['background_color'] ?? null,
                        'ts_icon' => $content['ts_icon'] ?? null,
                        'ts_title' => $content['ts_title'] ?? null,
                        'ts_description' => $content['ts_description'] ?? null,
                        'select_tours' => $content['select_tours'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('inner_banner' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'background_type_desktop'        => $content['background_type_desktop'] ?? null,
                        'background_image_desktop'       => $content['background_image_desktop'] ?? null,
                        'background_video_desktop'       => $content['background_video_desktop'] ?? null,
                        'background_type_mobile'         => $content['background_type_mobile'] ?? null,
                        'background_image_mobile'        => $content['background_image_mobile'] ?? null,
                        'background_video_mobile'        => $content['background_video_mobile'] ?? null,
                        'select_rating'                  => $content['select_rating'] ?? null,
                        'rating_text'                    => $content['rating_text'] ?? null,
                        'inner_banner_heading'           => $content['inner_banner_heading'] ?? null,
                        'inner_banner_short_description' => $content['inner_banner_short_description'] ?? null,
                        'inner_counter_repeater'         => $content['inner_counter_repeater'] ?? null,
                        'id'                             => $content['id'] ?? null,
                        'class'                          => $content['class'] ?? null,
                        'hide_section'                   => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('tour_section_with_load_more' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'background_color' => $content['background_color'] ?? null,
                        'ompt_icon' => $content['ompt_icon'] ?? null,
                        'ompt_title' => $content['ompt_title'] ?? null,
                        'ompt_description' => $content['ompt_description'] ?? null,
                        'load_more_button_text' => $content['load_more_button_text'] ?? null,
                        'select_tour_category' => $content['select_tour_category'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('image_with_content_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'iwc_background_color' => $content['iwc_background_color'] ?? null,
                        'iwc_image_position' => $content['iwc_image_position'] ?? null,
                        'iwc_image' => $content['iwc_image'] ?? null,
                        'iwc_icon' => $content['iwc_icon'] ?? null,
                        'iwc_title' => $content['iwc_title'] ?? null,
                        'iwc_description' => $content['iwc_description'] ?? null,
                        'iwc_button_text' => $content['iwc_button_text'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('what_you_will_see_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'wyws_background_color' => $content['wyws_background_color'] ?? null,
                        'wyws_icon' => $content['wyws_icon'] ?? null,
                        'wyws_title' => $content['wyws_title'] ?? null,
                        'wyws_description' => $content['wyws_description'] ?? null,
                        'wyws_locations' => $content['wyws_locations'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('what_sets_us_apart_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout' => $content['acf_fc_layout'] ?? '',
                        'wsua_background_color' => $content['wsua_background_color'] ?? null,
                        'wsua_icon' => $content['wsua_icon'] ?? null,
                        'wsua_title' => $content['wsua_title'] ?? null,
                        'wsua_description' => $content['wsua_description'] ?? null,
                        'wyws_steps' => $content['wyws_steps'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('general_content' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout'       => $content['acf_fc_layout'] ?? '',
                        'description'  => $content['description'] ?? null,
                        'id'           => $content['id'] ?? null,
                        'class'        => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('gift_card_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout'             => $content['acf_fc_layout'] ?? '',
                        'background_color'   => $content['background_color'] ?? null,
                        'icon'               => $content['icon'] ?? null,
                        'heading'            => $content['heading'] ?? null,
                        'subtitle'           => $content['subtitle'] ?? null,
                        'description'        => $content['description'] ?? null,
                        'gift_card_options'    => $content['gift_card_options'] ?? null,
                        'gift_card_details'    => $content['gift_card_details'] ?? null,
                        'tampa_description'    => $content['tampa_description'] ?? null,
                        'sarasota_description' => $content['sarasota_description'] ?? null,
                        'st_pete_description'  => $content['st_pete_description'] ?? null,
                        'tampa_button'         => $content['tampa_button'] ?? null,
                        'sarasota_button'      => $content['sarasota_button'] ?? null,
                        'stpete_button'        => $content['stpete_button'] ?? null,
                        'id'                 => $content['id'] ?? null,
                        'class'              => $content['class'] ?? null,
                        'hide_section'       => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('icon_cards_section' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout'           => $content['acf_fc_layout'] ?? '',
                        'background_color' => $content['background_color'] ?? null,
                        'icon'             => $content['icon'] ?? null,
                        'heading'          => $content['heading'] ?? null,
                        'description'      => $content['description'] ?? null,
                        'icon_cards'       => $content['icon_cards'] ?? null,
                        'id'               => $content['id'] ?? null,
                        'class'            => $content['class'] ?? null,
                        'hide_section'     => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('current_openings' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout'           => $content['acf_fc_layout'] ?? '',
                        'icon'             => $content['icon'] ?? null,
                        'main_title'       => $content['main_title'] ?? null,
                        'main_description' => $content['main_description'] ?? null,
                        'openings'         => $content['openings'] ?? null,
                        'id'               => $content['id'] ?? null,
                        'class'            => $content['class'] ?? null,
                        'hide_section'     => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('influencer_requirements' === ($content['acf_fc_layout'] ?? '')) {
                    $data[] = (object)[
                        'layout'              => $content['acf_fc_layout'] ?? '',
                        'ip_background_color' => $content['ip_background_color'] ?? null,
                        'ip_icon'             => $content['ip_icon'] ?? null,
                        'ip_title'            => $content['ip_title'] ?? null,
                        'ip_description'      => $content['ip_description'] ?? null,
                        'requirments'         => $content['requirments'] ?? null,
                        'id'                  => $content['id'] ?? null,
                        'class'               => $content['class'] ?? null,
                        'hide_section'        => $content['hide_section'] ?? null,
                    ];
                    continue;
                }

                if ('blog_listing' === ($content['acf_fc_layout'] ?? '')) {
                    $paged      = max(1, (int) get_query_var('paged'));
                    $blog_query = new \WP_Query([
                        'post_type'      => 'post',
                        'post_status'    => 'publish',
                        'posts_per_page' => 9,
                        'paged'          => $paged,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'no_found_rows'  => false,
                    ]);

                    $posts = [];
                    if ($blog_query->have_posts()) {
                        foreach ($blog_query->posts as $post) {
                            $post_id    = $post->ID;
                            $thumb_id   = get_post_thumbnail_id($post_id);
                            $categories = get_the_category($post_id);
                            $category   = !empty($categories) ? $categories[0] : null;

                            $posts[] = [
                                'id'           => $post_id,
                                'title'        => get_the_title($post_id),
                                'permalink'    => get_permalink($post_id),
                                'author'       => get_the_author_meta('display_name', $post->post_author),
                                'author_url'   => get_author_posts_url($post->post_author),
                                'date'         => get_the_date('', $post_id),
                                'date_time'    => get_the_date('c', $post_id),
                                'category'     => $category ? [
                                    'name' => $category->name,
                                    'url'  => get_category_link($category->term_id),
                                ] : null,
                                'thumbnail'    => $thumb_id ? [
                                    'url' => wp_get_attachment_image_url($thumb_id, 'large') ?: '',
                                    'alt' => get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ?: get_the_title($post_id),
                                ] : null,
                                'excerpt'      => wp_trim_words( wp_strip_all_tags( get_the_excerpt($post_id) ), 20, '...' ),
                            ];
                        }
                    }

                    $data[] = (object)[
                        'layout'         => $content['acf_fc_layout'] ?? '',
                        'id'             => $content['id'] ?? null,
                        'class'          => $content['extra_class'] ?? null,
                        'hide_section'   => $content['hide_section'] ?? null,
                        'posts'          => $posts,
                        'max_num_pages'  => (int) $blog_query->max_num_pages,
                        'paged'          => $paged,
                    ];

                    wp_reset_postdata();
                    continue;
                }

                $data[] = (object)$content;
            }
        }

        return $data;
    }

    /**
     * SVG path data for each county, keyed by county_key.
     */
    public function getCountySvgPaths(): array
    {
        return [
            'pinellas'     => 'M58.2024 394.222C72.6419 393.854 87.3807 394.208 101.834 394.207C105.812 393.486 107.901 399.427 107.814 402.868C107.324 422.079 110.984 446.352 107.096 465.018C105.464 466.955 103.919 467.833 102.092 468.959C99.2789 470.688 95.9737 478.208 95.6562 481.178C94.5135 491.867 115.954 500.695 123.024 506.223C125.699 508.046 127.143 512.054 129.346 514.146C132.832 517.463 136.136 518.119 139.152 522.238C141.172 534.501 134.136 546.401 132.153 557.768C129.555 572.674 135 570.515 119.502 576.806C112.156 580.189 97.2636 584.676 89.2652 584.96C80.1459 570.963 70.6274 560.375 60.7787 547.097C57.128 541.577 54.507 534.771 50.6093 529.451C42.9151 518.943 29.625 511.428 33.3359 496.563C35.2707 488.808 37.7825 482.402 40.2819 474.904C41.7802 470.409 41.1143 463.297 42.2933 458.633C46.0816 443.656 49.9606 428.718 53.386 413.649C54.2866 409.76 56.5276 397.085 58.2024 394.222Z',
            'hillsborough' => 'M133.96 391.663C141.551 390.993 150.301 392.335 158.05 392.231C167.238 392.107 176.46 393.156 185.685 393.214L326.284 393.162L326.331 597.139C312.634 596.527 296.113 596.921 282.181 596.921L203.752 596.93L157.242 596.939C159.746 593.831 161.965 592 164.482 588.427C167.529 584.104 170.636 578.495 173.99 574.612C176.525 571.677 182.351 568.888 184.973 565.478C193.531 554.346 201.032 545.412 211.174 535.725C210.066 530.364 210.225 526.117 208.375 520.105C204.97 509.034 200.84 499.795 199.607 488.09L197.269 486.742C195.012 486.72 181.46 496.026 177.94 497.981C181.496 506.325 183.669 521.142 184.339 530.205C178.345 528.706 169.296 526.241 163.316 525.545C163.257 520.681 163.299 515.813 163.441 510.95C167.108 505.949 167.457 501.927 163.333 496.966C158.324 490.945 158.334 474 152.748 469.966C148.716 467.053 137.846 466.649 133.277 462.14C128.758 457.68 121.944 458.185 116.374 455.494L115.848 455.237C115.442 448.418 115.668 439.676 115.671 432.701L115.705 393.593C122.045 392.377 127.558 392.124 133.96 391.663Z',
            'pasco'        => 'M265.133 272.641L320.758 272.588C322.125 274.772 323.971 278.178 324.097 280.744C324.489 289.336 323.991 298.141 323.675 306.773C323.157 320.94 324.472 336.24 323.473 350.268C320.829 352.928 310.243 351.158 305.9 351.986C303.78 356.62 304.791 378.96 305.053 384.691C302.554 385.467 298.728 386.635 296.116 386.531C278.815 385.848 261.306 385.847 244.017 385.849L123.481 385.873L62.8594 385.931C64.951 373.59 70.8617 367.153 75.5092 356.057C81.768 341.113 87.3298 325.773 93.4149 310.743C96.8939 302.188 97.3546 290.596 109.06 289.697C123.571 288.583 138.381 290.318 152.756 290.406L262.263 290.414C264.126 283.578 264.422 279.561 265.133 272.641Z',
            'hernando'     => 'M121.468 188.182C124.546 187.663 139.248 188.479 143.544 188.499L175.353 188.503C180.617 188.489 185.964 188.401 191.22 188.558C197.134 188.734 192.192 198.851 198.067 199.449C204.034 200.057 210.16 199.858 216.164 199.846C228.118 199.839 240.071 199.76 252.024 199.611C253.608 203.365 254.093 208.328 255.896 212.787C258.508 219.246 263.541 227.138 265.636 233.355C266.883 233.302 268.132 233.332 269.375 233.442C279.567 234.309 292.011 237.229 300.167 243.819C302.568 245.759 305.742 249.606 307.833 252.052C318.867 255.046 324.413 247.922 323.8 263.476C313.799 264.112 297.99 263.581 287.556 263.564C279.427 263.538 261.924 264.135 254.532 263.431C254.614 269.017 253.915 276.598 253.472 282.215C236.829 282.774 217.899 282.37 201.032 282.369L103.81 282.401C102.46 272.077 103.134 270.074 105.67 260.075C106.354 256.369 104.575 248.886 105.525 245.511C110.897 226.435 110.312 209.007 111.238 189.309C114.973 188.695 117.73 188.456 121.468 188.182Z',
            'citrus'       => 'M167.57 49.3347C172.382 49.3928 176.467 51.54 180.562 51.9377C186.47 52.5114 193.997 52.2902 200.032 52.3026C207.996 57.9115 222.608 75.8729 229.177 83.022L262.245 119.659C274.82 133.845 280.178 135.505 277.879 156.978C267.798 167.983 262.777 183.145 254.239 191.489C238.589 192.05 220.592 190.832 205.307 191.847C205.236 188.489 205.265 185.034 205.251 181.666L205.258 180.874C202.97 178.919 196.176 181.482 193.371 181.244C167.636 179.07 141.237 181.834 115.57 179.796C113.759 169.713 115.715 166.782 117.279 156.971C117.757 153.976 116.491 147.275 116.748 143.937C117.051 139.995 117.837 135.969 118.345 132.048C119.565 122.63 120.581 110.513 118.869 101.198C116.454 88.0568 100.597 77.5027 99.632 63.2535C99.5145 61.5218 100.897 60.2289 102.162 59.1618C109.393 53.0603 116.375 60.8155 124.161 62.7064C130.154 64.1624 133.702 64.471 139.79 65.0713C145.043 57.9527 158.932 51.1223 167.57 49.3347Z',
            'sumter'       => 'M331.547 88.9074C335.139 88.7695 353.991 88.1386 357.286 91.2031C357.923 91.7958 358.645 98.6785 358.585 100.146C357.936 116.239 357.911 132.238 357.914 148.354L357.934 288.602L357.956 323.121C357.957 326.56 358.178 337.417 357.544 340.148L356.697 340.584C351.683 340.123 347.832 333.953 344.191 330.78C342.573 329.37 341.276 328.426 339.524 327.187C337.633 328.942 334.929 331.666 332.384 331.924C329.033 328.956 331.213 307.831 331.244 302.61C331.316 290.918 331.305 279.148 331.298 267.457L331.302 244.211C322.945 245.573 307.354 246.522 302.214 238.229C295.591 227.545 282.287 228.903 270.741 226.828C270.066 223.037 269.793 221.589 268.473 217.896C266.439 212.2 258.559 199.827 262.651 194.258C267.725 187.354 271.035 185.561 272.943 177.006C278.37 173.025 280.406 168.913 283.825 163.224C283.888 152.251 283.88 141.279 283.801 130.307C273.847 124.395 268.748 114.926 261.452 106.346C258.957 103.411 246.094 93.8872 248.855 89.7387C256.779 89.2527 267.181 89.5365 275.39 89.5445C294.391 89.5626 312.547 89.7457 331.547 88.9074Z',
            'polk'         => 'M410.022 315.831C412.159 315.391 413.633 315.542 415.796 315.616L417.905 329.564L443.866 329.604C451.391 329.619 460.095 330.071 467.287 328.429C468.392 335.455 469.23 353.646 468.276 360.484C472.573 360.255 499.75 359.575 501.333 361.631C509.239 371.909 513.319 384.851 511.558 397.922C511.199 400.597 511.665 405.979 512.201 408.555L512.348 409.237C520.462 408.984 530.492 409.739 538.749 410.169C538.682 416.14 538.988 426.892 538.35 432.403C548.655 432.182 564.361 432.03 574.333 433.264C572.976 438.874 571.123 443.953 570.116 449.894C569.167 455.498 568.781 461.151 567.637 466.613C571.087 470.613 573.331 478.872 575.752 484.06C580.492 494.221 585.866 507.802 592.376 516.93C598.539 519.812 603.825 519.613 608.587 522.468C613.154 525.212 616.688 533.805 619.553 538.123C621.335 540.81 623.96 541.656 626.142 544.334C632.682 552.377 638.713 567.163 646.628 573.006C647.488 579.342 645.759 587.615 648.051 593.844C648.552 595.197 649 596.54 649.475 597.901C637.094 599.001 620.178 598.859 607.549 598.899L545.626 598.939C475.176 599.098 404.728 598.788 334.284 598.012C330.803 572.572 334.063 551.402 334.241 526.511C334.47 494.496 334.25 462.153 334.271 430.119L334.302 405.417C334.315 399.66 334.568 390.629 333.396 385.203C311.268 388.482 311.95 380.009 314.446 361.39C318.201 360.872 328.878 360.776 331.055 359.639C332.977 357.415 332.326 345.183 332.379 341.414C335.209 339.064 337.574 337.911 340.931 336.574C345.139 342.613 346.274 347.626 353.464 350.017C364.171 342.444 366.884 345.45 368.219 330.426C378.735 332.279 393.378 331.545 404.427 331.484C404.254 326.606 404.174 321.726 404.188 316.845C406.016 316.39 408.141 316.123 410.022 315.831Z',
            'manatee'      => 'M151.094 605.177L325.007 605.19C325.569 608.258 326.096 611.348 326.469 614.447C328.942 635.002 327.223 655.64 327.422 676.247C327.547 689.269 331.019 764.768 325.176 770.975C322.126 774.216 286.752 772.669 281.452 772.678C276.098 772.572 270.749 772.319 265.409 771.924C262.291 763.128 263.798 758.473 264.015 749.455C264.429 732.283 266.04 717.435 263.263 700.295C255.925 701.319 248.544 702.011 241.143 702.374C231.8 702.858 223.187 702.139 213.964 701.794L181.66 700.743C171.716 700.379 161.626 700.787 151.616 700.605C142.074 701.474 136.158 687.509 129.754 683.904C110.768 673.228 108.746 675.019 108.012 651.729C125.697 644.897 132.055 633.308 141.626 618.141C144.548 613.512 146.809 608.83 151.094 605.177Z',
            'hardee'       => 'M344.926 605.341C358.68 604.259 375.766 606.134 389.439 606.17L500.718 606.143C504.376 619.581 501.973 635.259 501.942 649.521L501.991 717.55C500.133 717.639 498.168 717.644 496.297 717.683L413.249 717.644C388.159 717.533 363.069 717.59 337.981 717.808C336.551 681.966 337.238 643.447 336.973 607.34C340.275 606.218 341.513 605.788 344.926 605.341Z',
            'highlands'    => 'M529.324 605.394C541.376 604.951 557.792 605.979 570.624 606.183C597.043 606.604 624.868 605.487 651.109 606.223L653.344 615.574C650.564 618.779 646.418 624.485 643.784 627.19C634.335 636.899 631.279 636.957 628.965 650.723C632.259 653.654 633.709 658.185 635.957 661.958C644.702 676.629 650.87 693.011 661.064 706.764C665.835 713.206 674.202 716.615 680.304 721.669C685.11 725.301 689.021 732.328 693.642 735.888C698.661 739.763 703.521 738.641 704.483 746.373C704.599 747.278 705.902 751.667 706.301 752.66C709.015 759.453 711.746 763.567 712.239 770.967C690.037 770.851 667.835 770.851 645.637 770.971C644.249 770.905 643.544 770.767 642.214 771.219C641.11 775.724 642.028 800.636 642.068 806.786C632.92 808.581 615.534 808.931 606.554 807.459C606.776 817.936 606.186 830.793 606.922 841.026C575.098 840.836 543.27 840.84 511.446 841.044C509.503 831.206 510.918 821.926 510.936 812.079C511.02 766.396 511.033 720.707 510.989 675.024C510.967 652.567 511.175 630.009 510.883 607.562C516.026 606.232 523.79 605.82 529.324 605.394Z',
        ];
    }

    /**
     * SVG abbreviation labels for each county, keyed by county_key.
     */
    public function getCountySvgAbbrs(): array
    {
        return [
            'pinellas'     => 'PLS',
            'hillsborough' => 'HIL',
            'pasco'        => 'PSC',
            'hernando'     => 'HND',
            'citrus'       => 'CTS',
            'sumter'       => 'SMR',
            'polk'         => 'PLK',
            'manatee'      => 'MNT',
            'hardee'       => 'HAR',
            'highlands'    => 'HL',
        ];
    }
}
