<?php
/**
 * Default theme options.
 *
 * @package MoreNews
 */

if (!function_exists('morenews_get_default_theme_options')):

/**
 * Get default theme options
 *
 * @since 1.0.0
 *
 * @return array Default theme options.
 */
function morenews_get_default_theme_options() {

    $defaults = array();
    // Preloader options section
    $defaults['enable_site_preloader'] = 0;
    $defaults['disable_sticky_header_option'] = 0;
    $defaults['disable_wp_emoji'] = 0;

    // Header options section
    $defaults['header_layout'] = 'header-layout-side';

    $defaults['show_top_header_section'] = 1;
    $defaults['show_social_menu_section'] = 1;
    $defaults['enable_sticky_header_option'] = 0;
    
    $defaults['show_date_section'] = 1;
    $defaults['show_time_section'] = 1;
    $defaults['top_header_time_format'] = 'en-US';

    $defaults['global_show_min_read']           = 'yes';
    $defaults['global_show_min_read_number']   = 200;
    $defaults['global_show_categories']           = 'yes';
    $defaults['global_show_home_menu_border']    = 'show-menu-border';
    $defaults['enable_site_mode_switch'] = 'aft-disable-mode-switch';
    $defaults['continue_switched_site_mode'] = 'aft-only-on-switched-page';
    $defaults['global_site_mode_setting']    = 'aft-default-mode';
    $defaults['aft_language_switcher']           = '';
    $defaults['show_primary_menu_desc']           = 0;
    $defaults['show_watch_online_section']           = 1;
    $defaults['aft_custom_icon']           = 'fas fa-play';
    $defaults['aft_custom_title']           = __('Live', 'morenews');
    $defaults['aft_custom_link']           = '';

    $defaults['disable_header_image_tint_overlay'] = 0;
    $defaults['select_header_image_mode'] = 'default';
    $defaults['show_offpanel_menu_section'] = 1;
    
    $defaults['banner_advertisement_section'] = '';
    $defaults['banner_advertisement_section_url'] = '';
    $defaults['banner_advertisement_open_on_new_tab'] = 1;
    $defaults['banner_advertisement_scope'] = 'site-wide';

    //Popular Tags
    $defaults['frontpage_popular_tags_settings'] = '';
    $defaults['show_popular_tags_section'] = 0;
    $defaults['frontpage_popular_tags_section_title'] = __('Popular Tags', 'morenews');
    $defaults['number_of_popular_tags'] = 10;
    $defaults['select_popular_tags_mode'] = 'post_tag';
    
    //Flash news
    $defaults['frontpage_flash_news_settings'] = '';
    $defaults['show_flash_news_section'] = 1;
    $defaults['flash_news_title'] = __('Exclusive', 'morenews');
    $defaults['select_flash_news_category'] = 0;
    $defaults['number_of_flash_news'] = 5;

    
    
    
    //List post section
    
    $defaults['show_featured_post_list_section'] = 0;
    for ($i = 1; $i <= 3; $i++) {
        $defaults['featured_post_list_section_title_'.$i] = sprintf(__('Posts List %d','morenews'),$i);
        $defaults['featured_post_list_category_section_' . $i] =0;
    }
    
    
    
    // breadcrumb options section
    $defaults['enable_breadcrumb'] = 1;
    $defaults['select_breadcrumb_mode'] = 'default';


    // Front-page Section.
    $defaults['show_main_news_section'] = 1;
    $defaults['main_banner_background_section'] = 0;


    $defaults['select_main_banner_layout_section'] = 'layout-3';

    $defaults['main_banner_news_section_title'] = __('Main News', 'morenews');
    $defaults['select_main_banner_order'] = 'order-1';
    $defaults['select_main_banner_order_2'] = 'order-1';
    $defaults['select_main_banner_order_3'] = 'order-1';

    $defaults['latest_tab_title'] = __('Latest', 'morenews');
    $defaults['popular_tab_title'] = __('Popular', 'morenews');
    $defaults['trending_tab_title'] = __('Trending', 'morenews');
    $defaults['select_trending_tab_news_category'] = 0;

    $defaults['main_trending_news_section_title'] = __('Trending Now', 'morenews');
    $defaults['select_trending_post_filterby'] = 'tag';
    $defaults['select_trending_post_category'] = 0;
    $defaults['select_trending_post_tag'] = 0;
    $defaults['trending_post_number_of_slides'] = 5;

    $defaults['main_popular_news_section_title'] = __('Popular', 'morenews');
    $defaults['select_popular_post_filterby'] = 'comment';
    $defaults['select_popular_post_category'] = 0;
    $defaults['select_popular_post_tag'] = 0;

    $defaults['main_update_news_section_title'] = __('Update', 'morenews');;
        $defaults['select_update_post_filterby'] = 'tag';
        $defaults['select_update_post_category'] = 0;
        $defaults['select_update_post_tag'] = 0;

    $defaults['main_latest_news_section_title'] = __('Latest', 'morenews');
    $defaults['select_banner_latest_post_filterby'] = 'cat';
    $defaults['select_banner_latest_post_category'] = 0;
    $defaults['select_latest_post_tag'] = 0;

    $defaults['select_slider_news_category'] = 0;
    $defaults['select_slider_news_tag'] = 0;
    $defaults['select_tab_section_mode'] = 'default';
    $defaults['select_trending_tab_news_category'] = 0;
    
    $defaults['select_main_banner_section_mode'] = 'banner-carousel';
    
    $defaults['select_main_banner_grid_option'] = 'grid-layout-1';
    
    $defaults['select_main_banner_carousel_option'] = 'carousel-layout-default';
    $defaults['select_main_banner_carousel_filterby'] = 'cat';

    $defaults['select_main_banner_carousel_position'] = 'aft-position-1';
    $defaults['select_editors_picks_news_position'] = 'aft-position-2';
    $defaults['select_trending_post_position'] = 'aft-position-3';

    $defaults['main_editors_picks_section_title'] = __("Editor's Picks", 'morenews');
    $defaults['select_editors_picks_filterby'] = 'cat';
    $defaults['select_editors_picks_news_category'] = 0;
    $defaults['select_editors_picks_news_tag'] = 0;

    $defaults['select_main_banner_carousel_layout_option'] = 'square-default';

    $defaults['main_banner_section_background_color'] = '#202020';
    $defaults['main_banner_section_secondary_background_color'] = '#212121';
    $defaults['main_banner_section_texts_color'] = '#ffffff';
    $defaults['main_banner_section_background_image'] = 0;
    $defaults['number_of_slides'] = 5;

    $defaults['editors_picks_title'] = __("Editor's Picks", 'morenews');
    $defaults['select_editors_picks_category'] = 0;

    $defaults['trending_slider_title'] = __("Trending Story", 'morenews');
    $defaults['select_trending_news_category'] = 0;
    $defaults['number_of_trending_slides'] = 5;

    $defaults['show_featured_posts_section'] = 1;
    $defaults['featured_news_section_title'] = __('Featured Posts', 'morenews');
    $defaults['number_of_featured_news'] = 4;
    //$defaults['select_featured_post'] = 0;
    
    $defaults['show_featured_category_section'] = 0;
    $defaults['select_featured_news_category'] = 0;
    
    $defaults['featured_category_section'] = __('Featured Categories', 'morenews');
    
    
    for ( $i = 1; $i < 5; $i++ ) {
        $defaults['featured_category_section_'.$i] =  sprintf(__('Post list Section Title %d','morenews'),$i);
        $defaults['featured_category_image_'.$i] = '';
        $defaults['select_featured_category_'.$i] = 0;
        $defaults['select_featured_page_'.$i] = 0;
        $defaults['featured_custom_image_'.$i] = '';
        $defaults['featured_custom_url_' .$i] = '';
        $defaults['featured_custom_text_' .$i] = __('View More','morenews');
    }
    
    $defaults['show_featured_category_page_section'] = 'category';

    $defaults['frontpage_content_alignment'] = 'align-content-left';
    $defaults['frontpage_content_type'] = 'frontpage-widgets';
    $defaults['frontpage_sticky_sidebar'] = 1;
    $defaults['frontpage_sticky_sidebar_position'] = 'sidebar-sticky-top';

    //layout options
    $defaults['global_content_layout'] = 'default-content-layout';
    $defaults['global_content_alignment'] = 'align-content-left';
    $defaults['global_fetch_content_image_setting'] = 'enable';
    $defaults['global_toggle_image_lazy_load_setting'] = 'enable';
    $defaults['global_decoding_image_async_setting'] = 'enable';
    $defaults['global_image_alignment'] = 'full-width-image';
    $defaults['global_post_date_author_setting'] = 'show-date-author';
    $defaults['small_grid_post_date_author_setting'] = 'show-date-only';
    $defaults['list_post_date_author_setting'] = 'show-date-only';
    $defaults['global_author_icon_gravatar_display_setting'] = 'display-icon';

    $defaults['global_excerpt_length'] = 18;
    $defaults['global_read_more_texts'] = __('Read More', 'morenews');
    $defaults['global_widget_excerpt_setting'] = 'default-excerpt';
    $defaults['global_date_display_setting'] = 'default-date';

    $defaults['archive_layout'] = 'archive-layout-list';
    $defaults['archive_layout_first_post_full'] = 0;
    $defaults['archive_pagination_view'] = 'archive-default';
    $defaults['archive_image_alignment_grid'] = 'archive-image-default';
    
    //grid column optoon
    $defaults['archive_grid_column_layout'] = 'grid-layout-two';
    $defaults['archive_image_alignment'] = 'archive-image-left';
    
    //masonary column option
    $defaults['archive_layout_masonry'] = 'masonry-layout-two';
    
    $defaults['archive_layout_full'] = 'full-image-first';
    
    $defaults['archive_content_view'] = 'archive-content-excerpt';
    $defaults['disable_main_banner_on_blog_archive'] = 1;
    $defaults['search_archive_content_view'] = 'all';
    $defaults['search_archive_enable_ajax'] = 1;
    $defaults['search_archive_ajax_results'] = 3;

    //Related posts
    $defaults['single_show_featured_image'] = 1;
    $defaults['single_featured_image_view'] = 'original';
    $defaults['global_single_content_mode'] = 'single-content-mode-default';
    $defaults['single_show_tags_list'] = 0;
    $defaults['single_show_theme_author_bio'] = 1;
    $defaults['single_show_mailchimp_subscriptions'] = 1;
    $defaults['single_show_trending_post_list'] = 1;
    $defaults['single_post_title_view']     = 'boxed';
    
    $defaults['single_post_social_share_view']     = 'after-title-default';
    $defaults['single_post_view_count']     = 'each-load-default';
   
    
    //Related posts
    $defaults['single_show_related_posts'] = 1;
    $defaults['single_related_posts_title']     = __( 'Related Stories', 'morenews' );
    $defaults['single_number_of_related_posts']  = 3;

    //Pagination.
    $defaults['site_pagination_type'] = 'default';

    

    // Footer.
    // Latest posts
    $defaults['frontpage_show_latest_posts'] = 1;
    $defaults['frontpage_latest_posts_section_title'] = __('You may have missed', 'morenews');
    $defaults['frontpage_latest_posts_category'] = 0;
    $defaults['number_of_frontpage_latest_posts'] = 4;

    $defaults['footer_copyright_text'] = __('Copyright &copy; All rights reserved.', 'morenews');
    $defaults['hide_footer_menu_section']  = 0;
    $defaults['hide_footer_site_title_section']  = 0;
    $defaults['hide_footer_copyright_credits']  = 0;
    $defaults['number_of_footer_widget']  = 3;
    $defaults['footer_background_color']  = '#202020';
    $defaults['footer_background_image'] = 0;
    $defaults['footer_texts_color']  = '#ffffff';
    $defaults['footer_credits_background_color']  = '#202020';
    $defaults['footer_credits_texts_color']  = '#ffffff';

    // font and color options
    $defaults['primary_color']     = '#ffffff';
    $defaults['primary_color_light']     = '#1f1f1f';
    $defaults['secondary_color']     = '#1665CF ';
    $defaults['light_background_color']     = '#eeeeee';
    $defaults['dark_background_color']     = '#000000';

    $defaults['top_header_background_color']    = '#202020';
    $defaults['top_header_texts_color']    = '#ffffff';

    $defaults['text_over_secondary_color']    = '#ffffff';
    $defaults['link_color']     = '#1665CF ';
    $defaults['site_wide_title_color']     = '#fff';
    $defaults['main_navigation_background_color_mode']     = 'secondary-color';
    $defaults['main_navigation_link_color']     = '#ffffff';
    $defaults['main_navigation_custom_background_color']     = '#202020';
    $defaults['main_navigation_badge_background']     = '#1665CF ';
    $defaults['main_navigation_badge_color']     = '#ffffff';
    $defaults['site_default_post_box_color']    = '#252525';
    $defaults['site_light_post_box_color']    = '#f9f9f9';
    $defaults['title_link_color']     = '#ffffff';
    $defaults['title_link_light_color']     = '#1f1f1f';
    $defaults['title_over_image_color']     = '#ffffff';
    $defaults['category_color_1']    = '#1665CF ';
    $defaults['category_color_2']    = '#d72924';
    $defaults['category_color_3']    = '#202020';
    $defaults['category_color_4']    = '#46af4b';
    $defaults['category_color_5']    = '#ea8d03';
    $defaults['category_color_6']    = '#ff5722';
    $defaults['category_color_7']    = '#9c27b0';
    $defaults['category_texts_color_1']    = '#ffffff ';
    $defaults['category_texts_color_2']    = '#ffffff';
    $defaults['category_texts_color_3']    = '#ffffff';
    $defaults['category_texts_color_4']    = '#ffffff';
    $defaults['category_texts_color_5']    = '#ffffff';
    $defaults['category_texts_color_6']    = '#ffffff';
    $defaults['category_texts_color_7']    = '#ffffff';


//font options additional value
    global $morenews_google_fonts;
    $morenews_google_fonts = array(
        'Jost:200,300,400,500,600,700,900'          => 'Jost',
        'Lato:400,300,400italic,900,700'            => 'Lato',
        'Noto+Serif:400,400italic,700'              => 'Noto Serif',
        'Open+Sans:400,400italic,600,700'           => 'Open Sans',
        'Oswald:300,400,700'                        => 'Oswald',
        'Raleway:400,300,500,600,700,900'           => 'Raleway',
        'Roboto:100,300,400,500,700'                => 'Roboto',        
        'Roboto+Slab:400,300,700'                   => 'Roboto Slab',
        'Source+Sans+Pro:400,400i,700,700i' => 'Source Sans Pro',
    );

    //font option
    $defaults['global_font_family_type']      = 'google';
    $defaults['site_title_font']      = 'Oswald:300,400,700';
    $defaults['primary_font']      = 'Open+Sans:400,400italic,600,700';
    $defaults['secondary_font']    = 'Oswald:300,400,700';
    $defaults['post_format_color']    = '#ffffff';
    $defaults['watch_online_background']    = '#d72924 ';
    $defaults['breaking_news_background']   = '#d72924 ';

    $defaults['global_widget_title_border']       = 'widget-title-border-bottom';
    $defaults['global_scroll_to_top_position']    = 'right';
    $defaults['global_show_comment_count']        = 'yes';
    $defaults['global_show_view_count']           = 'yes';
    $defaults['global_show_primary_menu_border']  = 'show-menu-border';

    $defaults['global_show_categories']           = 'yes';
    $defaults['global_number_of_categories']        = 'all';
    $defaults['global_custom_number_of_categories']  = 3;

    $defaults['global_site_layout_setting']    = 'wide';
    $defaults['global_site_layout_topbottom_gaps']    = true;

    $defaults['site_title_uppercase']    = true;

    //font size
    $defaults['site_title_font_size']    = 50;
    $defaults['title_font_weight']    = 700;
    $defaults['letter_spacing']    = 0;
    $defaults['title_line_height']  = 1.3;
    $defaults['line_height']        = 1.5;
    $defaults['global_font_size']   = 16;
    $defaults['title_type_1']    = 27;
    $defaults['title_type_2']    = 20;
    $defaults['title_type_3']    = 16;
    $defaults['title_type_4']    = 16;
    $defaults['morenews_page_posts_title_font_size']    = 42;
    $defaults['morenews_page_posts_paragraph_font_size']    = 16;
    $defaults['morenews_section_title_font_size']    = 16;


    // Pass through filter.
    $defaults = apply_filters('morenews_filter_default_theme_options', $defaults);

	return $defaults;

}

endif;
