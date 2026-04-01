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
                        'background_image' => $content['background_image'] ?? null,
                        'mobile_video' => $content['background_video_mobile'] ?? null,
                        'select_rating' => $content['select_rating'] ?? null,
                        'rating_text' => $content['rating_text'] ?? null,
                        'inner_banner_heading' => $content['inner_banner_heading'] ?? null,
                        'inner_banner_short_description' => $content['inner_banner_short_description'] ?? null,
                        'inner_counter_repeater' => $content['inner_counter_repeater'] ?? null,
                        'id' => $content['id'] ?? null,
                        'class' => $content['class'] ?? null,
                        'hide_section' => $content['hide_section'] ?? null,
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
                $data[] = (object)$content;
            }
        }

        return $data;
    }
}
