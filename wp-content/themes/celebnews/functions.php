<?php
if (!function_exists('celebnews_theme_enqueue_styles')) {
    add_action('wp_enqueue_scripts', 'celebnews_theme_enqueue_styles');

    function celebnews_theme_enqueue_styles()
    {
        $celebnews_version = wp_get_theme()->get('Version');
        $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        $parent_style = 'morenews-style';
        wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap' . $min . '.css');
        wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
        wp_enqueue_style(
            'celebnews',
            get_stylesheet_directory_uri() . '/style.css',
            array('bootstrap', $parent_style),
            wp_get_theme()->get('Version')
        );

        // Enqueue RTL Styles if the site is in RTL mode
        if (is_rtl()) {
            wp_enqueue_style(
                'morenews-rtl',
                get_template_directory_uri() . '/rtl.css',
                array($parent_style),
                $celebnews_version
            );
        }
    }
}

function celebnews_custom_header_setup($default_custom_header)
{
    $default_custom_header['default-text-color'] = 'ffffff';
    return $default_custom_header;
}
add_filter('morenews_custom_header_args', 'celebnews_custom_header_setup', 1);

function celebnews_filter_default_theme_options($defaults)
{
    $defaults['global_site_mode_setting']    = 'aft-dark-mode';
    $defaults['dark_background_color']     = '#000000';
    $defaults['show_popular_tags_section']  = 1;
    $defaults['select_popular_tags_mode']  = 'category';
    $defaults['site_title_font_size'] = 38;
    $defaults['site_title_uppercase']  = 0;
    $defaults['show_primary_menu_desc']  = 0;
    $defaults['select_header_image_mode']  = 'above';
    $defaults['disable_header_image_tint_overlay']  = 1;
    $defaults['flash_news_title'] = __('Breaking News', 'celebnews');
    $defaults['aft_custom_title']           = __('Subscribe', 'celebnews');
    $defaults['select_main_banner_layout_section'] = 'layout-1';
    $defaults['select_main_banner_order'] = 'order-1';
    $defaults['select_update_post_filterby'] = 'cat';
    $defaults['secondary_color'] = '#FFCC00';
    $defaults['frontpage_content_type']  = 'frontpage-widgets-and-content';
    $defaults['featured_news_section_title'] = __('Featured News', 'celebnews');
    $defaults['show_featured_post_list_section']  = 1;
    $defaults['featured_post_list_section_title_1']           = __('General News', 'celebnews');
    $defaults['featured_post_list_section_title_2']           = __('Global News', 'celebnews');
    $defaults['featured_post_list_section_title_3']           = __('More News', 'celebnews');
    return $defaults;
}
add_filter('morenews_filter_default_theme_options', 'celebnews_filter_default_theme_options', 1);