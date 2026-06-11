<?php

/**
 * Option Panel
 *
 * @package MoreNews
 */

$morenews_default = morenews_get_default_theme_options();
/*theme option panel info*/
require get_template_directory() . '/inc/customizer/frontpage-options.php';

//font and color options
require get_template_directory() . '/inc/customizer/font-color-options.php';

//selective refresh
require get_template_directory() . '/inc/customizer/customizer-refresh.php';


/**
 * Front-page options section
 *
 * @package MoreNews
 */


// Add Front-page Options Panel.
$wp_customize->add_panel(
  'site_header_option_panel',
  array(
    'title' => __('Header Options', 'morenews'),
    'priority' => 198,
    'capability' => 'edit_theme_options',
  )
);

/**
 * Header section
 *
 * @package MoreNews
 */

// Front-page Section.
$wp_customize->add_section(
  'header_options_settings',
  array(
    'title' => __('Header Settings', 'morenews'),
    'priority' => 49,
    'capability' => 'edit_theme_options',
    'panel' => 'site_header_option_panel',
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'enable_site_mode_switch',
  array(
    'default' => $morenews_default['enable_site_mode_switch'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'enable_site_mode_switch',
  array(
    'label' => __('Site Mode Switch', 'morenews'),
    'section' => 'header_builder',
    'settings' => 'enable_site_mode_switch',
    'type' => 'select',
    'choices' => array(
      'aft-enable-mode-switch' => __('Enable', 'morenews'),
      'aft-disable-mode-switch' => __('Disable', 'morenews'),
    ),
    'priority' => 5,
  )
);

// Setting - sticky_header_option.
$wp_customize->add_setting(
  'disable_sticky_header_option',
  array(
    'default'           => $morenews_default['disable_sticky_header_option'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);
$wp_customize->add_control(
  'disable_sticky_header_option',
  array(
    'label'    => __('Disable Sticky Header', 'morenews'),
    'section'  => 'header_builder',
    'type'     => 'checkbox',
    'priority' => 5,
    'description' => __('Sticky header works only with builder at bottom.', 'morenews'),

  )
);

//section title
$wp_customize->add_setting(
  'show_top_header_section_title',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Section_Title(
    $wp_customize,
    'show_top_header_section_title',
    array(
      'label' => __("Top Header Section", 'morenews'),
      'section' => 'header_builder',
      'priority' => 10,

    )
  )
);


// Setting - show_site_title_section.
$wp_customize->add_setting(
  'show_top_header_section',
  array(
    'default' => $morenews_default['show_top_header_section'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'show_top_header_section',
  array(
    'label' => __('Show Top Header', 'morenews'),
    'section' => 'header_builder',
    'settings' => 'show_top_header_section',
    'type' => 'checkbox',
    'priority' => 10,
    //'active_callback' => 'morenews_top_header_status'
  )
);


// Setting - show_site_title_section.
$wp_customize->add_setting(
  'show_date_section',
  array(
    'default' => $morenews_default['show_date_section'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);
$wp_customize->add_control(
  'show_date_section',
  array(
    'label' => __('Show Date', 'morenews'),
    'section' => 'header_builder',
    'type' => 'checkbox',
    'priority' => 5,
    'active_callback' => function ($control) {
      return (
        morenews_top_header_status($control)
        &&
        morenews_is_inactive_builder($control)
      );
    },
  )
);

// Setting - show_site_title_section.
$wp_customize->add_setting(
  'show_time_section',
  array(
    'default' => $morenews_default['show_time_section'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);
$wp_customize->add_control(
  'show_time_section',
  array(
    'label' => __('Show Time', 'morenews'),
    'section' => 'header_builder',
    'type' => 'checkbox',
    'priority' => 5,
    'active_callback' => 'morenews_top_header_status'
  )
);

// Setting - select_main_banner_section_mode.
$wp_customize->add_setting(
  'top_header_time_format',
  array(
    'default' => $morenews_default['top_header_time_format'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'top_header_time_format',
  array(
    'label' => __('Time Format', 'morenews'),
    'section' => 'header_builder',
    'type' => 'select',
    'choices' => array(
      'en-US' => __('12 hours', 'morenews'),
      'en-GB' => __('24 hours', 'morenews'),
      'en-WP' => __('From WordPress Settings', 'morenews'),
    ),
    'priority' => 5,
    'active_callback' => function ($control) {
      return (
        morenews_top_header_status($control)
        &&
        morenews_show_time_status($control)


      );
    },

  )
);



// Advertisement Section.
$wp_customize->add_section(
  'frontpage_advertisement_settings',
  array(
    'title' => __('Header Advertisement', 'morenews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'site_header_option_panel',
  )
);



// Advertisement Section.
// $wp_customize->add_section(
//   'frontpage_advertisement_settings',
//   array(
//     'title' => __('Header Advertisement', 'morenews'),
//     'priority' => 50,
//     'capability' => 'edit_theme_options',
//     'panel' => 'site_header_option_panel',
//   )
// );


//section title
$wp_customize->add_setting(
  'banner_advertisement_section_title',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Section_Title(
    $wp_customize,
    'banner_advertisement_section_title',
    array(
      'label' => __("Header Advertisement", 'morenews'),
      'section' => 'header_builder',
      'priority' => 120,

    )
  )
);


// Setting banner_advertisement_section.
$wp_customize->add_setting(
  'banner_advertisement_section',
  array(
    'default' => $morenews_default['banner_advertisement_section'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  )
);




$wp_customize->add_control(
  new WP_Customize_Cropped_Image_Control(
    $wp_customize,
    'banner_advertisement_section',
    array(
      'label' => __('Header Section Advertisement', 'morenews'),
      'description' => esc_html(sprintf(__('Recommended Size %1$s px X %2$s px', 'morenews'), 930, 110)),
      'section' => 'header_builder',
      'width' => 930,
      'height' => 110,
      'flex_width' => true,
      'flex_height' => true,
      'priority' => 120,
    )
  )
);

/*banner_advertisement_section_url*/
$wp_customize->add_setting(
  'banner_advertisement_section_url',
  array(
    'default' => $morenews_default['banner_advertisement_section_url'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'esc_url_raw',
  )
);
$wp_customize->add_control(
  'banner_advertisement_section_url',
  array(
    'label' => __('URL Link', 'morenews'),
    'section' => 'header_builder',
    'type' => 'text',
    'priority' => 130,
  )
);

// Setting - Unlock Pro Features for Header Builder
$wp_customize->add_setting(
  'unlock_pro_features_header',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Unlock_Pro_Features(
    $wp_customize,
    'unlock_pro_features_header',
    array(
      'label' => __("Premium Header Builder", 'morenews'),
      'section' => 'header_builder',
      'priority' => 130,
      'features' => array(
        'layouts'  => __('Multi-Row Element Slots', 'morenews'),
        'elements' => __('Off-Canvas & HTML Blocks', 'morenews'),
        'widgets'  => __('Triple Header Widget Areas', 'morenews'),
        'ads'      => __('Precision Ad Display Scopes', 'morenews'),
        'bg_mode'  => __('Full-Width Image Backgrounds', 'morenews'),
        'styling'  => __('Overlay & Tint Customization', 'morenews'),
        'menu'     => __('Icon-Enhanced Custom Menus', 'morenews'),
        'icons'    => __('Premium Icon Library Access', 'morenews')
      ),
    )
  )
);


// Add Theme Options Panel.
$wp_customize->add_panel(
  'theme_option_panel',
  array(
    'title' => __('Theme Options', 'morenews'),
    'priority' => 200,
    'capability' => 'edit_theme_options',
  )
);





// Breadcrumb Section.
$wp_customize->add_section(
  'site_breadcrumb_settings',
  array(
    'title' => __('Breadcrumb Options', 'morenews'),
    'priority' => 49,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);


// Setting - breadcrumb.
$wp_customize->add_setting(
  'enable_breadcrumb',
  array(
    'default' => $morenews_default['enable_breadcrumb'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'enable_breadcrumb',
  array(
    'label' => __('Show breadcrumbs', 'morenews'),
    'section' => 'site_breadcrumb_settings',
    'type' => 'checkbox',
    'priority' => 10,
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'select_breadcrumb_mode',
  array(
    'default' => $default['select_breadcrumb_mode'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'select_breadcrumb_mode',
  array(
    'label' => __('Select Breadcrumbs', 'morenews'),
    'description' => __("Please ensure that you have enabled the plugin's breadcrumbs before choosing other than Default", 'morenews'),
    'section' => 'site_breadcrumb_settings',
    'settings' => 'select_breadcrumb_mode',
    'type' => 'select',
    'choices' => array(
      'default' => __('Default', 'morenews'),
      'yoast' => __('Yoast SEO', 'morenews'),
      'rankmath' => __('Rank Math', 'morenews'),
      'bcn' => __('NavXT', 'morenews'),
    ),
    'priority' => 100,
  )
);




/**
 * Layout options section
 *
 * @package MoreNews
 */

// Layout Section.
$wp_customize->add_section(
  'site_layout_settings',
  array(
    'title' => __('Global Settings', 'morenews'),
    'priority' => 9,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);


// Setting - preloader.
$wp_customize->add_setting(
  'enable_site_preloader',
  array(
    'default' => $morenews_default['enable_site_preloader'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'enable_site_preloader',
  array(
    'label' => __('Enable Preloader', 'morenews'),
    'section' => 'site_layout_settings',
    'type' => 'checkbox',
    'priority' => 10,
  )
);


// Setting - Disable Emoji Script.
$wp_customize->add_setting(
  'disable_wp_emoji',
  array(
    'default'           => $morenews_default['disable_wp_emoji'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'disable_wp_emoji',
  array(
    'label'    => __('Disable Emoji Script', 'morenews'),
    'description'       => __('GDPR friendly & better performance', 'morenews'),
    'section'  => 'site_layout_settings', // Use your preferred section.
    'type'     => 'checkbox',
    'priority' => 10,
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_content_alignment',
  array(
    'default' => $morenews_default['global_content_alignment'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_content_alignment',
  array(
    'label' => __('Global Content Alignment', 'morenews'),
    'section' => 'site_layout_settings',
    'type' => 'select',
    'choices' => array(
      'align-content-left' => __('Content - Primary sidebar', 'morenews'),
      'align-content-right' => __('Primary sidebar - Content', 'morenews'),
      'full-width-content' => __('Full width content', 'morenews')
    ),
    'priority' => 130,
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_fetch_content_image_setting',
  array(
    'default'           => $default['global_fetch_content_image_setting'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_fetch_content_image_setting',
  array(
    'label'       => __('Also Show Content Image in Archive', 'morenews'),
    'description'       => __('If there is no Post Featured image set', 'morenews'),
    'section'     => 'site_layout_settings',
    'type'        => 'select',
    'choices'               => array(
      'enable' => __('Enable ', 'morenews'),
      'disable' => __('Disable', 'morenews'),

    ),
    'priority'    => 130,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_toggle_image_lazy_load_setting',
  array(
    'default'           => $default['global_toggle_image_lazy_load_setting'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_toggle_image_lazy_load_setting',
  array(
    'label'       => __('Image Lazy Loading', 'morenews'),
    'description'       => __('Set for better performance', 'morenews'),
    'section'     => 'site_layout_settings',
    'type'        => 'select',
    'choices'               => array(
      'enable' => __('Enable ', 'morenews'),
      'disable' => __('Disable', 'morenews'),

    ),
    'priority'    => 130,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_decoding_image_async_setting',
  array(
    'default'           => $default['global_decoding_image_async_setting'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_decoding_image_async_setting',
  array(
    'label'       => __('Image Async Decoding', 'morenews'),
    'description'       => __('Set to enhance rendering speed', 'morenews'),
    'section'     => 'site_layout_settings',
    'type'        => 'select',
    'choices'               => array(
      'enable' => __('Enable ', 'morenews'),
      'disable' => __('Disable', 'morenews'),

    ),
    'priority'    => 130,
  )
);



// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_scroll_to_top_position',
  array(
    'default' => $morenews_default['global_scroll_to_top_position'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_scroll_to_top_position',
  array(
    'label' => __('Scroll to Top Position', 'morenews'),
    'section' => 'site_layout_settings',
    'settings' => 'global_scroll_to_top_position',
    'type' => 'select',
    'choices' => array(
      'right' => __('Right', 'morenews'),
      'left' => __('Left', 'morenews'),

    ),
    'priority' => 130,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_show_comment_count',
  array(
    'default' => $morenews_default['global_show_comment_count'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_show_comment_count',
  array(
    'label' => __('Comment Count', 'morenews'),
    'section' => 'site_layout_settings',
    'type' => 'select',
    'choices' => array(
      'yes' => __('Show', 'morenews'),
      'no' => __('Hide', 'morenews'),

    ),
    'priority' => 130,
  )
);


// Setting - Unlock Pro Global & Performance Features
$wp_customize->add_setting(
  'unlock_pro_global_settings',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Unlock_Pro_Features(
    $wp_customize,
    'unlock_pro_global_settings',
    array(
      'label' => __("Site-Wide Performance Pack", 'morenews'),
      'section' => 'site_layout_settings',
      'priority' => 130,
      'features' => array(
        'tones'    => __('15+ One-Click Theme Tones', 'morenews'),
        'speed'    => __('Pro Lazy Load & LCP Speed-Up', 'morenews'),
        'toc'      => __('Auto-Generated Table of Contents', 'morenews'),
        'views'    => __('Native Post View Counter', 'morenews'),
        'widgets'  => __('5+ Modern Widget Title Styles', 'morenews'),
        'marketing' => __('Global Newsletter Subscription Bar', 'morenews'),
        'image'    => __('Advanced Image Fit & Hover Zoom', 'morenews'),
        'seo'      => __('GDPR & SEO Core Optimization', 'morenews'),
        'ux'       => __('Smooth Scroll & Premium UI Logic', 'morenews')
      ),
    )
  )
);


// Global Section.
$wp_customize->add_section(
  'site_categories_settings',
  array(
    'title' => __('Categories Settings', 'morenews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_show_categories',
  array(
    'default' => $morenews_default['global_show_categories'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_show_categories',
  array(
    'label' => __('Post Categories', 'morenews'),
    'section' => 'site_categories_settings',
    'type' => 'select',
    'choices' => array(
      'yes' => __('Show', 'morenews'),
      'no' => __('Hide', 'morenews'),

    ),
    'priority' => 130,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_number_of_categories',
  array(
    'default' => $morenews_default['global_number_of_categories'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_number_of_categories',
  array(
    'label' => __('Categories to be displayed', 'morenews'),
    'section' => 'site_categories_settings',
    'type' => 'select',
    'choices' => array(
      'all' => __('Show All', 'morenews'),
      'one' => __('Top One Category', 'morenews'),

    ),
    'priority' => 130,
    'active_callback' => 'morenews_global_show_category_number_status'

  )
);





// Setting - sticky_header_option.
$wp_customize->add_setting(
  'global_custom_number_of_categories',
  array(
    'default' => $morenews_default['global_custom_number_of_categories'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  )
);
$wp_customize->add_control(
  'global_custom_number_of_categories',
  array(
    'label' => __('Number of Categories', 'morenews'),
    'section' => 'site_categories_settings',
    'type' => 'number',
    'priority' => 130,
    'active_callback' => 'morenews_global_show_custom_category_number_status'
  )
);

// Global Section.
$wp_customize->add_section(
  'site_author_and_date_settings',
  array(
    'title' => __('Author and Date Settings', 'morenews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_author_icon_gravatar_display_setting',
  array(
    'default' => $morenews_default['global_author_icon_gravatar_display_setting'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_author_icon_gravatar_display_setting',
  array(
    'label' => __('Author Icon', 'morenews'),
    'section' => 'site_author_and_date_settings',
    'type' => 'select',
    'choices' => array(
      'display-gravatar' => __('Show Gravatar', 'morenews'),
      'display-icon' => __('Show Icon', 'morenews'),
      'display-none' => __('None', 'morenews'),
    ),
    'priority' => 130,

  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_date_display_setting',
  array(
    'default' => $morenews_default['global_date_display_setting'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_date_display_setting',
  array(
    'label' => __('Date Format', 'morenews'),
    'section' => 'site_author_and_date_settings',
    'type' => 'select',
    'choices' => array(
      'default-date' => __('WordPress Default Date Format', 'morenews'),
      'theme-date' => __('Ago Date Format', 'morenews'),
    ),
    'priority' => 130,

  )
);

// Setting - Unlock Pro Meta & Editorial Control
$wp_customize->add_setting(
  'unlock_pro_author_date_settings',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Unlock_Pro_Features(
    $wp_customize,
    'unlock_pro_author_date_settings',
    array(
      'label' => __("Editorial Authority Pack", 'morenews'),
      'section' => 'site_author_and_date_settings',
      'priority' => 130,
      'features' => array(
        'seo_date'  => __('SEO-Ready "Modified" Date Logic', 'morenews'),
        'dual_date' => __('Show Published & Updated Dates', 'morenews'),
        'spotlight' => __('Spotlight & Feature Meta Control', 'morenews'),
        'grid'      => __('Grid, List & Archive Meta Toggles', 'morenews'),
        'gravatar'  => __('Author Gravatar & Profile Branding', 'morenews'),
        'layout'    => __('Per-Section Metadata Customization', 'morenews'),
        'format'    => __('9+ Professional Date Formats', 'morenews'),
        'minimal'   => __('Minimalist Meta View Modes', 'morenews')
      ),
    )
  )
);


//========== minutes read count options ===============

// Global Section.
$wp_customize->add_section(
  'site_min_read_settings',
  array(
    'title' => __('Minutes Read Count', 'morenews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_show_min_read',
  array(
    'default' => $morenews_default['global_show_min_read'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_show_min_read',
  array(
    'label' => __('Minutes Read Count', 'morenews'),
    'section' => 'site_min_read_settings',
    'type' => 'select',
    'choices' => array(
      'yes' => __('Show', 'morenews'),
      'no' => __('Hide', 'morenews'),

    ),
    'priority' => 130,
  )
);


// Setting - Unlock Pro Reading Time Features
$wp_customize->add_setting(
  'unlock_pro_read_count',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Unlock_Pro_Features(
    $wp_customize,
    'unlock_pro_read_count',
    array(
      'label' => __("Smart Reading Metrics", 'morenews'),
      'section' => 'site_min_read_settings',
      'priority' => 130,
      'features' => array(
        'accurate'  => __('Precision Read-Time Algorithm', 'morenews'),
        'wpm'       => __('Adjustable Reading Speed Logic', 'morenews'),
        'archive'   => __('Archive & Grid Reading Estimates', 'morenews'),
        'single'    => __('Single Post Engagement Metrics', 'morenews'),
        'layout'    => __('Per-Section Visibility Control', 'morenews'),
        'retention' => __('Enhanced Reader Retention Tools', 'morenews')
      ),
    )
  )
);


// Global Section.
$wp_customize->add_section(
  'site_excerpt_settings',
  array(
    'title' => __('Excerpt Settings', 'morenews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);



// Setting - related posts.
$wp_customize->add_setting(
  'global_read_more_texts',
  array(
    'default' => $morenews_default['global_read_more_texts'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  'global_read_more_texts',
  array(
    'label' => __('Global Excerpt Read More', 'morenews'),
    'section' => 'site_excerpt_settings',
    'type' => 'text',
    'priority' => 130,

  )
);


//============= Watch Online Section ==========
//section title
$wp_customize->add_setting(
  'show_watch_online_section_section_title',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Section_Title(
    $wp_customize,
    'show_watch_online_section_section_title',
    array(
      'label' => __("Primary Menu Section", 'morenews'),
      'section' => 'header_builder',
      'priority' => 100,

    )
  )
);

$wp_customize->add_setting(
  'show_primary_menu_desc',
  array(
    'default' => $morenews_default['show_primary_menu_desc'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'show_primary_menu_desc',
  array(
    'label' => __('Show Primary Menu Description', 'morenews'),
    'section' => 'header_builder',
    'type' => 'checkbox',
    'priority' => 100,

  )
);

$wp_customize->add_setting(
  'show_watch_online_section',
  array(
    'default' => $morenews_default['show_watch_online_section'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'show_watch_online_section',
  array(
    'label' => __('Enable Custom Menu Section', 'morenews'),
    'section' => 'header_builder',
    'type' => 'checkbox',
    'priority' => 100,
    'active_callback' => 'morenews_is_inactive_builder'

  )
);


// Setting - related posts.
$wp_customize->add_setting(
  'aft_custom_title',
  array(
    'default' => $morenews_default['aft_custom_title'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  'aft_custom_title',
  array(
    'label' => __('Title', 'morenews'),
    'section' => 'header_builder',
    'type' => 'text',
    'priority' => 100,
    'active_callback' => 'morenews_show_watch_online_section_status'
  )
);

// Setting - related posts.
$wp_customize->add_setting(
  'aft_custom_link',
  array(
    'default' => $morenews_default['aft_custom_link'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'esc_url_raw',
  )
);

$wp_customize->add_control(
  'aft_custom_link',
  array(
    'label' => __('Link', 'morenews'),
    'section' => 'header_builder',
    'settings' => 'aft_custom_link',
    'type' => 'text',
    'priority' => 100,
    'active_callback' => 'morenews_show_watch_online_section_status'
  )
);


//========== single posts options ===============

// Single Section.
$wp_customize->add_section(
  'site_single_posts_settings',
  array(
    'title' => __('Single Post', 'morenews'),
    'priority' => 9,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

// Setting - related posts.
$wp_customize->add_setting(
  'single_show_featured_image',
  array(
    'default' => $morenews_default['single_show_featured_image'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'single_show_featured_image',
  array(
    'label' => __('Show Featured Image', 'morenews'),
    'section' => 'site_single_posts_settings',
    'type' => 'checkbox',
    'priority' => 100,
  )
);


$wp_customize->add_setting(
  'single_featured_image_view',
  array(
    'default' => $morenews_default['single_featured_image_view'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);


$wp_customize->add_control(
  'single_featured_image_view',
  array(
    'label' => __('Featured Image Width', 'morenews'),
    'section' => 'site_single_posts_settings',
    'type' => 'select',
    'choices' => array(
      'full' => __('Full - Default', 'morenews'),
      'original' => __('Original', 'morenews')
    ),
    'priority' => 100,
    'active_callback' => 'morenews_featured_image_status'
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_single_content_mode',
  array(
    'default'           => $default['global_single_content_mode'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_single_content_mode',
  array(
    'label'       => __('Single Content Mode', 'morenews'),
    'section'     => 'site_single_posts_settings',
    'settings'     => 'global_single_content_mode',
    'type'        => 'select',
    'choices'               => array(
      'single-content-mode-default' => __('Default', 'morenews'),
      'single-content-mode-boxed' => __('Spacious', 'morenews'),
    ),
    'priority'    => 100,
  )
);




//Social share option

if (class_exists('Jetpack') && Jetpack::is_module_active('sharedaddy')) :
  $wp_customize->add_setting(
    'single_post_social_share_view',
    array(
      'default' => $morenews_default['single_post_social_share_view'],
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'morenews_sanitize_select',
    )
  );

  $wp_customize->add_control(
    'single_post_social_share_view',
    array(
      'label' => __('Social Share Option', 'morenews'),
      'description' => __('Social Share from Jetpack plugin', 'morenews'),
      'section' => 'site_single_posts_settings',
      'type' => 'select',
      'choices' => array(
        'after-title-default' => __('Top - Default', 'morenews'),
        'after-content' => __('Bottom', 'morenews'),
      ),
      'priority' => 100,
    )
  );
endif;


// Setting - trending posts.
$wp_customize->add_setting(
  'single_show_theme_author_bio',
  array(
    'default' => $default['single_show_theme_author_bio'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'single_show_theme_author_bio',
  array(
    'label' => __('Show Author Bio under Content', 'morenews'),
    'section' => 'site_single_posts_settings',
    'settings' => 'single_show_theme_author_bio',
    'type' => 'checkbox',
    'priority' => 100,
  )
);

// Setting - trending posts.
$wp_customize->add_setting(
  'single_show_tags_list',
  array(
    'default' => $default['single_show_tags_list'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'single_show_tags_list',
  array(
    'label' => __('Show Tags under Content', 'morenews'),
    'section' => 'site_single_posts_settings',
    'settings' => 'single_show_tags_list',
    'type' => 'checkbox',
    'priority' => 100,
  )
);


//========== related posts  options ===============

$wp_customize->add_setting(
  'single_related_posts_section_title',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Section_Title(
    $wp_customize,
    'single_related_posts_section_title',
    array(
      'label' => __("Related Posts Settings", 'morenews'),
      'section' => 'site_single_posts_settings',
      'priority' => 100,

    )
  )
);

// Setting - related posts.
$wp_customize->add_setting(
  'single_show_related_posts',
  array(
    'default' => $morenews_default['single_show_related_posts'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'single_show_related_posts',
  array(
    'label' => __('Enable Related Posts', 'morenews'),
    'section' => 'site_single_posts_settings',
    'type' => 'checkbox',
    'priority' => 100,
  )
);

// Setting - related posts.
$wp_customize->add_setting(
  'single_related_posts_title',
  array(
    'default' => $morenews_default['single_related_posts_title'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  'single_related_posts_title',
  array(
    'label' => __('Title', 'morenews'),
    'section' => 'site_single_posts_settings',
    'settings' => 'single_related_posts_title',
    'type' => 'text',
    'priority' => 100,
    'active_callback' => 'morenews_related_posts_status'
  )
);


// Setting - The Ultimate Single Post & Revenue Hub
$wp_customize->add_setting(
  'unlock_pro_single_post_master',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Unlock_Pro_Features(
    $wp_customize,
    'unlock_pro_single_post_master',
    array(
      'label'    => __("Revenue & Engagement Hub", 'morenews'),
      'section'  => 'site_single_posts_settings',
      'priority' => 100,
      'features' => array(
        'ads'       => __('Smart Auto-Ad Injection', 'morenews'),
        'sticky'    => __('High-Revenue Sticky Sidebars', 'morenews'),
        'monetize'  => __('Sponsor & Source Attribution', 'morenews'),
        'leads'     => __('Integrated Newsletter Sign-ups', 'morenews'),
        'social'    => __('11+ Pro Social Share Styles', 'morenews'),
        'retention' => __('Trending & Saved Post Lists', 'morenews'),
        'interact'  => __('Post Reactions & Bookmarks', 'morenews'),
        'widgets'   => __('6 Premium Post Widget Areas', 'morenews'),
        'video'     => __('Featured Video & Subtitles', 'morenews'),
        'progress'  => __('Reading Progress Indicator', 'morenews'),
        'dark'      => __('Pro Dark Mode Toggle', 'morenews'),
        'seo'       => __('LCP Optimized Page Speed', 'morenews')
      ),
    )
  )
);



/**
 * Archive options section
 *
 * @package MoreNews
 */

// Archive Section.
$wp_customize->add_section(
  'site_archive_settings',
  array(
    'title' => __('Archive Settings', 'morenews'),
    'priority' => 9,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);


// Disable main banner in blog
$wp_customize->add_setting(
  'disable_main_banner_on_blog_archive',
  array(
    'default'           => $default['disable_main_banner_on_blog_archive'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'disable_main_banner_on_blog_archive',
  array(
    'label'    => __('Disable Main Banner on Blog', 'morenews'),
    'section'  => 'site_archive_settings',
    'type'     => 'checkbox',
    'priority' => 50,
    'active_callback' => 'morenews_main_banner_section_status'
  )
);

//Setting - archive content view of news.
$wp_customize->add_setting(
  'archive_layout',
  array(
    'default' => $morenews_default['archive_layout'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'archive_layout',
  array(
    'label' => __('Archive layout', 'morenews'),
    'description' => __('Select layout for archive', 'morenews'),
    'section' => 'site_archive_settings',
    'settings' => 'archive_layout',
    'type' => 'select',
    'choices' => array(

      'archive-layout-list' => __('List', 'morenews'),
      'archive-layout-full' => __('Full', 'morenews'),

    ),
    'priority' => 130,
  )
);


// Setting - archive content view of news.
$wp_customize->add_setting(
  'archive_image_alignment',
  array(
    'default' => $morenews_default['archive_image_alignment'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'archive_image_alignment',
  array(
    'label' => __('Image Alignment', 'morenews'),
    'description' => __('Select image alignment for archive', 'morenews'),
    'section' => 'site_archive_settings',
    'type' => 'select',
    'choices' => array(
      'archive-image-left' => __('Left', 'morenews'),
      'archive-image-right' => __('Right', 'morenews'),
    ),
    'priority' => 130,
    'active_callback' => 'morenews_archive_image_status'
  )
);



//Settings - archive content full view
$wp_customize->add_setting(
  'archive_layout_full',
  array(
    'default' => $morenews_default['archive_layout_full'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'archive_layout_full',
  array(
    'label' => __('Select Title Position', 'morenews'),
    'description' => __('Select full layout for archive', 'morenews'),
    'section' => 'site_archive_settings',
    'type' => 'select',
    'choices' => array(
      'full-image-first' => __('After Image', 'morenews'),
      'full-title-first' => __('Before Image', 'morenews'),

    ),
    'priority' => 130,
    'active_callback' => 'morenews_archive_full_status'
  )
);

//Setting - archive content view of news.
$wp_customize->add_setting(
  'archive_content_view',
  array(
    'default' => $morenews_default['archive_content_view'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'archive_content_view',
  array(
    'label' => __('Content View', 'morenews'),
    'description' => __('Select content view for archive', 'morenews'),
    'section' => 'site_archive_settings',
    'type' => 'select',
    'choices' => array(
      'archive-content-excerpt' => __('Post Excerpt', 'morenews'),
      'archive-content-full' => __('Full Content', 'morenews'),
      'archive-content-none' => __('None', 'morenews'),

    ),
    'priority' => 130,
  )
);


// Setting - Unlock Pro Features for Archive/Blog
$wp_customize->add_setting(
  'unlock_pro_features_archives',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Unlock_Pro_Features(
    $wp_customize,
    'unlock_pro_features_archives',
    array(
      'label' => __("Global Archive Engine", 'morenews'),
      'section' => 'site_archive_settings',
      'priority' => 130,
      'features' => array(
        'masonry'   => __('Masonry & Premium Grid Styles', 'morenews'),
        'ajax'      => __('Smart "Load More" Pagination', 'morenews'),
        'scroll'    => __('Infinite Scroll Engagement', 'morenews'),
        'full_post' => __('Featured First-Post Highlighting', 'morenews'),
        'align'     => __('Alternate Multi-Image Alignment', 'morenews'),
        'view'      => __('Advanced Content View Logic', 'morenews'),
        'category'  => __('Per-Category Layout Overrides', 'morenews')
      ),
    )
  )
);

// Breadcrumb Section.
$wp_customize->add_section(
  'site_search_settings',
  array(
    'title' => __('Search Options', 'morenews'),
    'priority' => 9,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

//Setting - archive content view of news.
$wp_customize->add_setting(
  'search_archive_content_view',
  array(
    'default' => $morenews_default['search_archive_content_view'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'search_archive_content_view',
  array(
    'label' => __('Search Results', 'morenews'),
    'description' => __('Select search results for archive', 'morenews'),
    'section' => 'site_search_settings',
    'type' => 'select',
    'choices' => array(
      'all' => __('All (Pages, Posts, etc)', 'morenews'),
      'posts' => __('Posts', 'morenews')
    ),
    'priority' => 130,
  )
);


//Setting - archive content view of news.
$wp_customize->add_setting(
  'search_archive_enable_ajax',
  array(
    'default' => $morenews_default['search_archive_enable_ajax'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  )
);

$wp_customize->add_control(
  'search_archive_enable_ajax',
  array(
    'label' => __('Ajax Results Dropdown', 'morenews'),
    'section' => 'site_search_settings',
    'type' => 'checkbox',
    'priority' => 130,
  )
);

// Setting - number of posts
$wp_customize->add_setting(
  'search_archive_ajax_results',
  array(
    'default' => $morenews_default['search_archive_ajax_results'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  )
);


//========== sidebar blocks options ===============

// Trending Section.
$wp_customize->add_section(
  'sidebar_block_settings',
  array(
    'title' => __('Sidebar Settings', 'morenews'),
    'priority' => 9,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);


// Setting - frontpage_sticky_sidebar.
$wp_customize->add_setting(
  'frontpage_sticky_sidebar',
  array(
    'default' => $default['frontpage_sticky_sidebar'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'frontpage_sticky_sidebar',
  array(
    'label' => __('Make Sidebar Sticky', 'morenews'),
    'section' => 'sidebar_block_settings',
    'type' => 'checkbox',
    'priority' => 100,

  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'frontpage_sticky_sidebar_position',
  array(
    'default' => $default['frontpage_sticky_sidebar_position'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_select',
  )
);

$wp_customize->add_control(
  'frontpage_sticky_sidebar_position',
  array(
    'label' => __('Sidebar Sticky Position', 'morenews'),
    'section' => 'sidebar_block_settings',
    'type' => 'select',
    'choices' => array(
      'sidebar-sticky-top' => __('Top', 'morenews'),
      'sidebar-sticky-bottom' => __('Bottom', 'morenews'),
    ),
    'priority' => 100,
    'active_callback' => 'frontpage_sticky_sidebar_status'
  )
);

//========== footer latest blog carousel options ===============

// Footer Section.
$wp_customize->add_section(
  'frontpage_latest_posts_settings',
  array(
    'title' => __('You May Have Missed', 'morenews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);
// Setting - latest blog carousel.
$wp_customize->add_setting(
  'frontpage_show_latest_posts',
  array(
    'default' => $morenews_default['frontpage_show_latest_posts'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'frontpage_show_latest_posts',
  array(
    'label' => __('Show Above Footer', 'morenews'),
    'section' => 'frontpage_latest_posts_settings',
    'type' => 'checkbox',
    'priority' => 100,
  )
);


// Setting - featured_news_section_title.
$wp_customize->add_setting(
  'frontpage_latest_posts_section_title',
  array(
    'default' => $morenews_default['frontpage_latest_posts_section_title'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
  )
);
$wp_customize->add_control(
  'frontpage_latest_posts_section_title',
  array(
    'label' => __('Posts Section Title', 'morenews'),
    'section' => 'frontpage_latest_posts_settings',
    'settings' => 'frontpage_latest_posts_section_title',
    'type' => 'text',
    'priority' => 100,
    'active_callback' => 'morenews_latest_news_section_status'

  )
);



//========== footer section options ===============
// Footer Section.
$wp_customize->add_section(
  'site_footer_settings',
  array(
    'title' => __('Footer', 'morenews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);


// Setting banner_advertisement_section.
$wp_customize->add_setting(
  'footer_background_image',
  array(
    'default' => $default['footer_background_image'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  )
);


$wp_customize->add_control(
  new WP_Customize_Cropped_Image_Control(
    $wp_customize,
    'footer_background_image',
    array(
      'label' => __('Footer Background Image', 'morenews'),
      'description' => esc_html(sprintf(__('Recommended Size %1$s px X %2$s px', 'morenews'), 1024, 800)),
      'section' => 'footer_builder',
      'width' => 1024,
      'height' => 800,
      'flex_width' => true,
      'flex_height' => true,
      'priority' => 100,
    )
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'footer_copyright_text',
  array(
    'default' => $morenews_default['footer_copyright_text'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  'footer_copyright_text',
  array(
    'label' => __('Copyright Text', 'morenews'),
    'section' => 'footer_builder',
    'settings' => 'footer_copyright_text',
    'type' => 'text',
    'priority' => 100,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'hide_footer_menu_section',
  array(
    'default' => $morenews_default['hide_footer_menu_section'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'morenews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'hide_footer_menu_section',
  array(
    'label' => __('Hide Footer Menu Section', 'morenews'),
    'section' => 'footer_builder',
    'type' => 'checkbox',
    'priority' => 100,
  )
);

// Setting - Unlock Pro Features for Footer Builder
$wp_customize->add_setting(
  'unlock_pro_features_footer',
  array(
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  new MoreNews_Unlock_Pro_Features(
    $wp_customize,
    'unlock_pro_features_footer',
    array(
      'label' => __("Professional Footer Builder", 'morenews'),
      'section' => 'footer_builder',
      'priority' => 100,
      'features' => array(
        'white_label' => __('Remove "Powered by" Credits', 'morenews'),
        'copyright'   => __('Custom Branding & Copyright Editor', 'morenews'),
        'layout'      => __('Unlimited Layout Combinations', 'morenews'),
        'widgets'     => __('Multi-Column Footer Widget Areas', 'morenews'),
        'bg_img'      => __('Full-Width Background Imagery', 'morenews'),
        'html'        => __('Custom HTML & Tracking Scripts', 'morenews'),
        'social'      => __('Integrated Social Brand Links', 'morenews'),
        'elements'    => __('All Premium Pro Footer Blocks', 'morenews')
      ),
    )
  )
);
