<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class TourSingle extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array<string>
     */
    protected static $views = ['partials.content-single-tours'];

    /**
     * Data passed to the Blade view before rendering.
     *
     * @return array<string, mixed>
     */
    public function with(): array
    {
        return [
            'tourSinglePageData' => $this->tourSinglePageData(),
        ];
    }

    /**
     * Aggregate all ACF data for the tour single page.
     *
     * @return array<string, mixed>
     */
    protected function tourSinglePageData(): array
    {
        // ── Image slider ──────────────────────────────────────────────────────
        $sliderRows  = get_field('image_slider');
        $imageSlider = [];

        if (! empty($sliderRows) && is_array($sliderRows)) {
            foreach ($sliderRows as $row) {
                $imageSlider[] = ['image' => $row['s_image'] ?? []];
            }
        }

        // ── Tour features repeater ────────────────────────────────────────────
        $featureRows  = get_field('tour_features');
        $tourFeatures = [];

        if (! empty($featureRows) && is_array($featureRows)) {
            foreach ($featureRows as $row) {
                $tourFeatures[] = [
                    'icon' => $row['tf_features_icon'] ?? [],
                    'text' => $row['tf_features_text'] ?? '',
                ];
            }
        }

        // ── Tabs with content repeater ────────────────────────────────────────
        $tabRows = get_field('tab_with_content');
        $tabs    = [];

        if (! empty($tabRows) && is_array($tabRows)) {
            foreach ($tabRows as $row) {
                $locations = [];

                if (! empty($row['twc_locations']) && is_array($row['twc_locations'])) {
                    foreach ($row['twc_locations'] as $loc) {
                        $locations[] = [
                            'icon'             => $loc['twc_l_icon']              ?? [],
                            'title'            => $loc['tec_l_title']             ?? '',
                            'shortDescription' => $loc['twc_l_short_description'] ?? '',
                            'buttonLink'       => $loc['twc_l_button_link']       ?? [],
                        ];
                    }
                }

                $tabs[] = [
                    'title'      => $row['twc_tab_title']   ?? '',
                    'content'    => $row['twc_tab_content'] ?? '',
                    'isMap'      => ! empty($row['twc_is_map']),
                    'twc_map_image'        => $row['twc_map_image']         ?? [],
                    'isLocation' => ! empty($row['twc_is_location']),
                    'locations'  => $locations,
                ];
            }
        }

        // ── Raw ACF groups ────────────────────────────────────────────────────
        $reviewBlock  = get_field('review_block')   ?: [];
        $priceBlock   = get_field('price_block')    ?: [];
        $flightGroup  = get_field('flight_duration') ?: [];
        $buttonGroup  = get_field('button_group')   ?: [];

        return [
            'hero' => [
                'title'              => get_the_title(),
                'featuredImageUrl'   => get_the_post_thumbnail_url(null, 'full') ?: '',
                'shortDescription'   => get_field('tb_short_description')   ?: '',
                'recommendedText'    => get_field('recommended_text')        ?: '',
                'departsFromText'    => get_field('departs_from_text')       ?: '',
                'passengerOfferText' => get_field('passenger_offer_text')    ?: '',
                'bestFor'            => get_field('best_for')                ?: '',
                'shortcodeBooking'   => get_field('shortcode_for_booking')   ?: '',
            ],
            'rating' => [
                'selectRating' => get_field('tb_select_rating') ?: '',
                'ratingText'   => get_field('tb_rating_text')   ?: '',
                'reviewBlock'  => [
                    'starRating' => $reviewBlock['select_star_rating'] ?? 0,
                    'reviewText' => $reviewBlock['review_text'] ?? '',
                ],
            ],
            'imageSlider'  => $imageSlider,
            'priceBlock'   => [
                'regularPrice'       => $priceBlock['regular_price']         ?? '',
                'offerPrice'         => $priceBlock['offer_price']           ?? '',
                'perPersonText'      => $priceBlock['per_person_text']       ?? '',
                'selectPriceTag'     => $priceBlock['select_price_tag']      ?? [],
                'selectBookingTag'   => $priceBlock['select_booking_tag']    ?? [],
                'tooltipForPriceTag' => $priceBlock['tooltip_for_price_tag'] ?? '',
            ],
            'flightInfo'   => [
                'durationText' => $flightGroup['flight_duration_text'] ?? '',
                'duration'     => $flightGroup['flight_duration']      ?? '',
            ],
            'buttons'      => [
                'bookNow'   => $buttonGroup['book_now_button']   ?? [],
                'learnMore' => $buttonGroup['learn_more_button'] ?? '',
            ],
            'tourFeatures' => $tourFeatures,
            'tabs'         => $tabs,
            'cta'          => [
                'backgroundImage'  => get_field('cta_background_image') ?: [],
                'iconImage'        => get_field('cta_icon_image')        ?: [],
                'title'            => get_field('cta_title')             ?: '',
                'shortDescription' => get_field('cta_short_description') ?: '',
                'bookNowButton'    => get_field('book_now_button')       ?: [],
            ],
            'meta'         => [
                'sectionId'    => get_field('id')           ?: '',
                'sectionClass' => get_field('class')        ?: '',
                'hideSection'  => get_field('hide_section') === 'yes',
            ],
        ];
    }
}
