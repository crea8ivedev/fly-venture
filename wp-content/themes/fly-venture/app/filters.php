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
