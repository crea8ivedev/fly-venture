<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class ArchiveComposer extends Composer
{
    protected static $views = ['partials.content-archive'];

    public function with(): array
    {
        $paged = max(1, (int) get_query_var('paged'));

        // Build archive title & description
        $archiveTitle = get_the_archive_title();
        $archiveDesc  = get_the_archive_description();

        // Resolve the active category ID for pre-selecting the filter
        $activeCatId = 0;
        if (is_category()) {
            $activeCatId = get_queried_object_id();
        }

        // Use the global WP_Query already set up by WordPress for this archive
        global $wp_query;

        $posts = [];
        if ($wp_query->have_posts()) {
            foreach ($wp_query->posts as $post) {
                $post_id    = $post->ID;
                $thumb_id   = get_post_thumbnail_id($post_id);
                $categories = get_the_category($post_id);
                $category   = !empty($categories) ? $categories[0] : null;

                $posts[] = [
                    'id'        => $post_id,
                    'title'     => get_the_title($post_id),
                    'permalink' => get_permalink($post_id),
                    'author'    => get_the_author_meta('display_name', $post->post_author),
                    'date'      => get_the_date('M j, Y', $post_id),
                    'category'  => $category ? [
                        'name' => $category->name,
                        'url'  => get_category_link($category->term_id),
                    ] : null,
                    'thumbnail' => $thumb_id ? [
                        'url' => wp_get_attachment_image_url($thumb_id, 'large') ?: '',
                        'alt' => get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ?: get_the_title($post_id),
                    ] : null,
                    'excerpt'   => wp_trim_words(wp_strip_all_tags(get_the_excerpt($post_id)), 20, '...'),
                ];
            }
        }

        return [
            'archiveTitle' => $archiveTitle,
            'archiveDesc'  => $archiveDesc,
            'activeCatId'  => $activeCatId,
            'posts'        => $posts,
            'maxNumPages'  => (int) $wp_query->max_num_pages,
            'paged'        => $paged,
            'categories'   => get_categories(['hide_empty' => true, 'orderby' => 'name', 'order' => 'ASC']),
        ];
    }
}
