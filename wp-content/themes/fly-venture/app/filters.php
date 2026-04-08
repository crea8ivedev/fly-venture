<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Prepend /blog/ to standard post permalinks.
 * e.g. /{slug}/ → /blog/{slug}/
 */
add_filter('post_link', function (string $permalink, \WP_Post $post): string {
    if ($post->post_status === 'auto-draft') {
        return $permalink;
    }
    // Only modify standard posts, not CPTs
    if ($post->post_type !== 'post') {
        return $permalink;
    }
    return home_url('/blog/' . $post->post_name . '/');
}, 10, 2);

/**
 * Add rewrite rule so /blog/{slug}/ resolves to the correct post.
 */
add_action('init', function () {
    add_rewrite_rule(
        '^blog/([^/]+)/?$',
        'index.php?name=$matches[1]',
        'top'
    );
});

/**
 * Auto-inject loading="lazy" on ALL images in the full HTML output.
 * Uses output buffering so it covers blade templates, widgets, everything.
 * Skips the admin and any img that already has a loading attribute.
 */
add_action('template_redirect', function () {
    if (is_admin()) {
        return;
    }

    ob_start(function (string $html): string {
        return preg_replace_callback(
            '/<img([^>]*)>/i',
            function (array $matches): string {
                // Already has loading= — leave it alone
                if (stripos($matches[1], 'loading=') !== false) {
                    return $matches[0];
                }
                return '<img' . $matches[1] . ' loading="lazy">';
            },
            $html
        );
    });
});
