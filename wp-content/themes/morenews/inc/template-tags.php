<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package MoreNews
 */

if (!function_exists('morenews_post_categories')) :
    function morenews_post_categories($morenews_is_single = false)
    {
        $morenews_global_show_categories = morenews_get_option('global_show_categories');
        if ($morenews_global_show_categories == 'no') {
            return;
        }


        $morenews_global_number_of_categories = morenews_get_option('global_number_of_categories');
        if ($morenews_global_number_of_categories == 'custom') {
            $show_category_number = morenews_get_option('global_custom_number_of_categories');
            $show_category_number = absint($show_category_number);
        } elseif ($morenews_global_number_of_categories == 'one') {
            $show_category_number = 1;
        } else {
            $show_category_number = 0;
        }

        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            global $post;
            $morenews_post_categories = get_the_category($post->ID);
            if ($morenews_post_categories) {

                $morenews_output = '<ul class="cat-links">';
                $category_count = 0;
                foreach ($morenews_post_categories as $post_category) {
                    $morenews_t_id = $post_category->term_id;
                    $morenews_color_id = "category_color_" . $morenews_t_id;

                    // retrieve the existing value(s) for this meta field. This returns an array
                    $morenews_term_meta = get_option($morenews_color_id);
                    $morenews_color_class = ($morenews_term_meta) ? $morenews_term_meta['color_class_term_meta'] : 'category-color-1';

                    $morenews_output .= '<li class="meta-category">
                             <a class="morenews-categories ' . esc_attr($morenews_color_class) . '" href="' . esc_url(get_category_link($post_category)) . '" aria-label="' . esc_html($post_category->name) . '">
                                 ' . esc_html($post_category->name) . '
                             </a>
                        </li>';

                    if ($morenews_is_single == false) {
                        if (++$category_count == $show_category_number) break;
                    }
                }
                $morenews_output .= '</ul>';
                echo wp_kses_post($morenews_output);
            }
        }
    }
endif;


if (!function_exists('morenews_get_category_color_class')) :

    function morenews_get_category_color_class($term_id)
    {

        $morenews_color_id = "category_color_" . $term_id;
        // retrieve the existing value(s) for this meta field. This returns an array
        $morenews_term_meta = get_option($morenews_color_id);
        $morenews_color_class = ($morenews_term_meta) ? $morenews_term_meta['color_class_term_meta'] : '';
        return $morenews_color_class;
    }
endif;

if (!function_exists('morenews_post_item_meta')) :

    function morenews_post_item_meta($morenews_post_display = 'spotlight-post')
    {

        global $post;
        if ('post' == get_post_type($post->ID)) :

            $morenews_author_id = $post->post_author;
            $morenews_date_display_setting = morenews_get_option('global_date_display_setting');
            $morenews_author_icon_gravatar_display_setting = morenews_get_option('global_author_icon_gravatar_display_setting');

            if ($morenews_post_display == 'list-post') {

                $morenews_post_meta = morenews_get_option('list_post_date_author_setting');
            } elseif ($morenews_post_display == 'grid-post') {
                $morenews_post_meta = morenews_get_option('small_grid_post_date_author_setting');
            } else {
                $morenews_post_meta = morenews_get_option('global_post_date_author_setting');
            }

            if ($morenews_post_meta == 'show-date-only') {
                $morenews_display_author = false;
                $morenews_display_date = true;
            } elseif ($morenews_post_meta == 'show-author-only') {
                $morenews_display_author = true;
                $morenews_display_date = false;
            } elseif (($morenews_post_meta == 'show-date-author')) {
                $morenews_display_author = true;
                $morenews_display_date = true;
            } else {
                $morenews_display_author = false;
                $morenews_display_date = false;
            }

?>



            <?php if ($morenews_display_author) : ?>
                <span class="item-metadata posts-author byline">
                    <?php if ($morenews_author_icon_gravatar_display_setting == 'display-gravatar') {
                        morenews_by_author($gravatar = true);
                    } elseif ($morenews_author_icon_gravatar_display_setting == 'display-icon') { ?>
                        <i class="far fa-user-circle"></i>
                    <?php morenews_by_author($gravatar = false);
                    } else {
                        morenews_by_author($gravatar = false);
                    } ?>
                </span>
            <?php endif; ?>


            <?php
            if ($morenews_display_date) : ?>
                <span class="item-metadata posts-date">
                    <i class="far fa-clock" aria-hidden="true"></i>
                    <?php
                    // Get the post's date components (year, month, day) for building the archive link
                    // $year  = get_the_time('Y');
                    // $month = get_the_time('m');
                    // $day   = get_the_time('d');

                    // Generate the URL for the date archive
                    // $date_archive_link = get_day_link($year, $month, $day);

                    if ($morenews_date_display_setting == 'default-date') {
                        // Wrap the date in an anchor tag pointing to the archive
                        $date_format = get_option('date_format');
                        $posted_on_text = sprintf(__('Posted on %s', 'morenews'), get_the_time($date_format));

                        // Create the link with translation-ready attributes
                        // echo '<a href="' . esc_url($date_archive_link) . '" aria-label="' . esc_attr($posted_on_text) . '">';
                        the_time($date_format); // This outputs the formatted date
                        // echo '</a>';
                    } else {
                        $time_ago = human_time_diff(get_the_time('U'), current_time('timestamp'));
                        $time_ago_text = sprintf(__('Posted on %s %s', 'morenews'), $time_ago, __('ago', 'morenews'));

                        // echo '<a href="' . esc_url($date_archive_link) . '" aria-label="' . esc_attr($time_ago_text) . '">';
                        echo esc_html($time_ago_text);
                        // echo '</a>';
                    }
                    ?>
                </span>
            <?php endif; ?>



<?php
        endif;
    }
endif;


if (!function_exists('morenews_post_item_tag')) :

    function morenews_post_item_tag($view = 'default')
    {

        $morenews_single_show_tags_list = morenews_get_option('single_show_tags_list');
        if ($morenews_single_show_tags_list) {
            if ('post' === get_post_type()) {

                /* translators: used between list items, there is a space after the comma */
                $tags_list = get_the_tag_list('', ' ');
                if ($tags_list) {
                    /* translators: 1: list of tags. */
                    printf('<span class="tags-links">' . esc_html('Tags: %1$s') . '</span>', $tags_list);
                }
            }
        }

        if (is_single()) {
            edit_post_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Edit <span class="screen-reader-text">%s</span>', 'morenews'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            );
        }
    }
endif;

if (!function_exists('morenews_preload_header_image')) :
    function morenews_preload_header_image()
    {
        // Check if there is a custom header image set for the theme.
        if (has_header_image()) {
            // Get the URL of the header image.
            $morenews_background = get_header_image();

            // Output the preload link for the header image.
            echo '<link rel="preload" href="' . esc_url($morenews_background) . '" as="image">';
        }
    }
endif;
add_action('wp_head', 'morenews_preload_header_image');


function morenews_live_search_scripts()
{

    // Check if AJAX search enabled
    $enable_ajax = morenews_get_option('search_archive_enable_ajax');
    $limit       = absint(morenews_get_option('search_archive_ajax_results'));

    if ($enable_ajax) {

        wp_enqueue_script(
            'morenews-live-search',
            get_template_directory_uri() . '/assets/search-script.js',
            array('jquery'),
            null,
            true
        );

        wp_localize_script('morenews-live-search', 'afLiveSearch', array(
            'ajax_url'       => admin_url('admin-ajax.php'),
            'searching_text' => __('Searching...', 'morenews'),
            'enabled'        => (bool) $enable_ajax,
            'results_count'  => $limit > 0 ? $limit : 5,
            'nonce'          => wp_create_nonce('morenews_live_search'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'morenews_live_search_scripts');

function morenews_live_search_ajax()
{

    // Security check
    if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'morenews_live_search')) {
        wp_send_json_error(['message' => __('Invalid security token.', 'morenews')]);
    }

    // Query text
    $query = isset($_POST['s']) ? sanitize_text_field(wp_unslash($_POST['s'])) : '';

    // Customizer controls
    $search_archive_content_view = morenews_get_option('search_archive_content_view');
    $limit = isset($_POST['limit']) ? absint($_POST['limit']) : absint(morenews_get_option('search_archive_ajax_results'));

    if ($limit < 1) {
        $limit = 5;
    }

    // Build query
    $args = array(
        's'              => $query,
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
    );

    if ($search_archive_content_view !== 'all') {
        $args['post_type'] = 'post';
    }

    $search = new WP_Query($args);

    // Wrapper
    echo '<div class="af-live-search-results" role="listbox" aria-label="' . esc_attr__('Live search results', 'morenews') . '">';

    if ($search->have_posts()) {

        echo '<ul class="af-live-search-list">';

        while ($search->have_posts()) {
            $search->the_post();

            echo '<li class="af-live-search-item" role="option" tabindex="-1">';
            do_action('morenews_action_loop_list', get_the_ID(), 'thumbnail', 0, false, false, false);
            echo '</li>';
        }

        echo '</ul>';

        // View all link
        echo '<div class="af-live-search-more">';
        echo '<a href="' . esc_url(get_search_link($query)) . '" class="view-all-results" aria-label="' . esc_attr__('View all search results', 'morenews') . '">';
        printf(esc_html__('View all results for "%s"', 'morenews'), esc_html($query));
        echo '</a>';
        echo '</div>';
    } else {

        echo '<p class="no-results" role="alert">';
        echo esc_html__('No results found.', 'morenews');
        echo '</p>';
    }

    echo '</div>';

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_morenews_live_search', 'morenews_live_search_ajax');
add_action('wp_ajax_nopriv_morenews_live_search', 'morenews_live_search_ajax');