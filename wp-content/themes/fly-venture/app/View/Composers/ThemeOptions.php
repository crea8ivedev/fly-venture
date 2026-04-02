<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class ThemeOptions extends Composer
{
    protected static $views = [
        'partials.header',
        'partials.footer',
        'sections.header',
        'sections.footer',
        'index',
        '404'
    ];

    public function with()
    {
        $header = get_field('header_options', 'option');
        $footer = get_field('footer_options', 'option');

        return [

            // 404
            'error_image'       => get_field('error_image', 'option') ?: null,
            'error_pre_heading' => get_field('error_pre_heading', 'option') ?: null,
            'error_heading'     => get_field('error_heading', 'option') ?: null,
            'go_home_button'    => get_field('go_home_button', 'option') ?: null,

            // Header
            'header_logo' => $header['header_logo'] ?? null,
            'announcement_text' => $header['announcement_text'] ?? null,
            'announcement_button' => $header['announcement_button'] ?? null,
            'header_phone_number' => $header['header_phone_number'] ?? null,
            'header_book_your_flight_button' => $header['header_book_your_flight_button'] ?? null,

            // Footer
            'footer_about_title' => $footer['footer_about_title'] ?? null,
            'footer_about_description' => $footer['footer_about_description'] ?? null,
            'social_logos' => $footer['social_logos'] ?? [],
            'footer_contact_detail_title' => $footer['footer_contact_detail_title'] ?? null,
            'footer_contact_details' => $footer['footer_contact_details'] ?? [],
            'footer_quick_link_title' => $footer['footer_quick_link_title'] ?? null,
            'footer_logo' => $footer['footer_logo'] ?? null,
            'footer_logo_mobile' => $footer['footer_logo_mobile'] ?? null,
            'footer_copyright_text' => $footer['footer_copyright_text'] ?? null,

//            Offer Popup
            'op_popup_image' => $footer['op_popup_image'] ?? null,
            'op_content_1' => $footer['op_content_1'] ?? null,
            'op_content_2' => $footer['op_content_2'] ?? null,
            'coupan_block' => $footer['coupan_block'] ?? null,
            'book_your_flight_button' => $footer['book_your_flight_button'] ?? null,
            'footer_book_flight_button' => $footer['footer_book_flight_button'] ?? null,
        ];
    }
}
