<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }

    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'footer_navigation'  => __('Footer Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ];

    register_sidebar([
            'name' => __('Primary', 'sage'),
            'id'   => 'sidebar-primary',
        ] + $config);

    register_sidebar([
            'name' => __('Footer', 'sage'),
            'id'   => 'sidebar-footer',
        ] + $config);
});

// =========================================================================
// Tours CPT + Taxonomies
// All registrations are hooked to 'init' per WP best practice.
// =========================================================================

// -------------------------------------------------------------------------
// 1. Tours Custom Post Type
// -------------------------------------------------------------------------
add_action('init', function () {

    $labels = [
        'name'                  => _x('Tours', 'Post type general name', 'sage'),
        'singular_name'         => _x('Tour', 'Post type singular name', 'sage'),
        'menu_name'             => _x('Tours', 'Admin menu text', 'sage'),
        'name_admin_bar'        => _x('Tour', 'Add New on toolbar', 'sage'),
        'add_new'               => __('Add New', 'sage'),
        'add_new_item'          => __('Add New Tour', 'sage'),
        'new_item'              => __('New Tour', 'sage'),
        'edit_item'             => __('Edit Tour', 'sage'),
        'view_item'             => __('View Tour', 'sage'),
        'all_items'             => __('All Tours', 'sage'),
        'search_items'          => __('Search Tours', 'sage'),
        'not_found'             => __('No tours found.', 'sage'),
        'not_found_in_trash'    => __('No tours found in Trash.', 'sage'),
        'featured_image'        => __('Tour Cover Image', 'sage'),
        'set_featured_image'    => __('Set cover image', 'sage'),
        'remove_featured_image' => __('Remove cover image', 'sage'),
        'use_featured_image'    => __('Use as cover image', 'sage'),
        'archives'              => __('Tour archives', 'sage'),
        'insert_into_item'      => __('Insert into tour', 'sage'),
        'uploaded_to_this_item' => __('Uploaded to this tour', 'sage'),
        'items_list'            => __('Tours list', 'sage'),
        'items_list_navigation' => __('Tours list navigation', 'sage'),
        'filter_items_list'     => __('Filter tours list', 'sage'),
    ];

    register_post_type('tours', [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'tours'],
        'capability_type'    => 'post',
        'has_archive'        => true,   // Enables /tours/ archive URL.
        'hierarchical'       => false,
        'menu_position'      => 5,      // Below Posts in the admin menu.
        'menu_icon'          => 'dashicons-location-alt',
        'show_in_rest'       => true,   // Block editor + REST API support.
        'rest_base'          => 'tours',
        'supports'           => [
            'title',
            'editor',
            'thumbnail',    // = featured image
            'excerpt',
            'custom-fields',
            'revisions',
        ],
    ]);

});

// -------------------------------------------------------------------------
// 2. Tour Category — hierarchical (like standard Categories)
// -------------------------------------------------------------------------
add_action('init', function () {

    $labels = [
        'name'                  => _x('Tour Categories', 'Taxonomy general name', 'sage'),
        'singular_name'         => _x('Tour Category', 'Taxonomy singular name', 'sage'),
        'search_items'          => __('Search Tour Categories', 'sage'),
        'all_items'             => __('All Tour Categories', 'sage'),
        'parent_item'           => __('Parent Tour Category', 'sage'),
        'parent_item_colon'     => __('Parent Tour Category:', 'sage'),
        'edit_item'             => __('Edit Tour Category', 'sage'),
        'update_item'           => __('Update Tour Category', 'sage'),
        'add_new_item'          => __('Add New Tour Category', 'sage'),
        'new_item_name'         => __('New Tour Category Name', 'sage'),
        'menu_name'             => __('Tour Categories', 'sage'),
        'not_found'             => __('No tour categories found.', 'sage'),
        'no_terms'              => __('No tour categories', 'sage'),
        'items_list'            => __('Tour categories list', 'sage'),
        'items_list_navigation' => __('Tour categories list navigation', 'sage'),
        'back_to_items'         => __('&larr; Go to Tour Categories', 'sage'),
    ];

    register_taxonomy('tour_category', ['tours'], [
        'labels'            => $labels,
        'hierarchical'      => true,   // Checkbox UI, like Categories.
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,   // Shows as a column in the Tours list table.
        'query_var'         => true,
        'rewrite'           => ['slug' => 'tour-category'],
        'show_in_rest'      => true,
        'rest_base'         => 'tour-categories',
    ]);

});

// -------------------------------------------------------------------------
// 3. Tour Tag — non-hierarchical (like standard Tags)
// -------------------------------------------------------------------------
add_action('init', function () {

    $labels = [
        'name'                       => _x('Tour Tags', 'Taxonomy general name', 'sage'),
        'singular_name'              => _x('Tour Tag', 'Taxonomy singular name', 'sage'),
        'search_items'               => __('Search Tour Tags', 'sage'),
        'popular_items'              => __('Popular Tour Tags', 'sage'),
        'all_items'                  => __('All Tour Tags', 'sage'),
        'parent_item'                => null,   // Null = no parent for flat taxonomy.
        'parent_item_colon'          => null,
        'edit_item'                  => __('Edit Tour Tag', 'sage'),
        'update_item'                => __('Update Tour Tag', 'sage'),
        'add_new_item'               => __('Add New Tour Tag', 'sage'),
        'new_item_name'              => __('New Tour Tag Name', 'sage'),
        'separate_items_with_commas' => __('Separate tour tags with commas', 'sage'),
        'add_or_remove_items'        => __('Add or remove tour tags', 'sage'),
        'choose_from_most_used'      => __('Choose from the most used tour tags', 'sage'),
        'not_found'                  => __('No tour tags found.', 'sage'),
        'menu_name'                  => __('Tour Tags', 'sage'),
        'items_list'                 => __('Tour tags list', 'sage'),
        'items_list_navigation'      => __('Tour tags list navigation', 'sage'),
        'back_to_items'              => __('&larr; Go to Tour Tags', 'sage'),
        'no_terms'                   => __('No tour tags', 'sage'),
    ];

    register_taxonomy('tour_tag', ['tours'], [
        'labels'            => $labels,
        'hierarchical'      => false,  // Tag-cloud UI, like Tags.
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'tour-tag'],
        'show_in_rest'      => true,
        'rest_base'         => 'tour-tags',
    ]);

});

// -------------------------------------------------------------------------
// 4. Tour Price Tag — non-hierarchical (like standard Tags)
// -------------------------------------------------------------------------
add_action('init', function () {

    $labels = [
        'name'                       => _x('Tour Price Tags', 'Taxonomy general name', 'sage'),
        'singular_name'              => _x('Tour Price Tag', 'Taxonomy singular name', 'sage'),
        'search_items'               => __('Search Tour Price Tags', 'sage'),
        'popular_items'              => __('Popular Tour Price Tags', 'sage'),
        'all_items'                  => __('All Tour Price Tags', 'sage'),
        'parent_item'                => null,   // Null = no parent for flat taxonomy.
        'parent_item_colon'          => null,
        'edit_item'                  => __('Edit Tour Price Tag', 'sage'),
        'update_item'                => __('Update Tour Price Tag', 'sage'),
        'add_new_item'               => __('Add New Tour Price Tag', 'sage'),
        'new_item_name'              => __('New Tour Price Tag Name', 'sage'),
        'separate_items_with_commas' => __('Separate tour price tags with commas', 'sage'),
        'add_or_remove_items'        => __('Add or remove tour price tags', 'sage'),
        'choose_from_most_used'      => __('Choose from the most used tour price tags', 'sage'),
        'not_found'                  => __('No tour price tags found.', 'sage'),
        'menu_name'                  => __('Tour Price Tags', 'sage'),
        'items_list'                 => __('Tour price tags list', 'sage'),
        'items_list_navigation'      => __('Tour price tags list navigation', 'sage'),
        'back_to_items'              => __('&larr; Go to Tour Price Tags', 'sage'),
        'no_terms'                   => __('No tour price tags', 'sage'),
    ];

    register_taxonomy('tour_price_tag', ['tours'], [
        'labels'            => $labels,
        'hierarchical'      => false,  // Tag-cloud UI, like Tags.
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,   // Shows as a column in the Tours list table.
        'query_var'         => true,
        'rewrite'           => ['slug' => 'tour-price-tag'],
        'show_in_rest'      => true,
        'rest_base'         => 'tour-price-tags',
    ]);

});
