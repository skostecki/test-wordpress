<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function morenews_widgets_init()
{
   
    register_sidebar(array(
        'name'          => __('Header Banner Ad Section', 'morenews'),
        'id'            => 'home-advertisement-widgets',
        'description'   => __('Add widgets for frontpage banner section advertisement.', 'morenews'),
        'before_widget' => '<div id="%1$s" class="widget morenews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="heading-line-before"></span><span class="heading-line">',
        'after_title' => '</span><span class="heading-line-after"></span></h2>',
    ));



    register_sidebar(array(
        'name'          => __('Off Canvas Drawer Menu Section', 'morenews'),
        'id'            => 'express-off-canvas-panel',
        'description'   => __('Add widgets for off-canvas section.', 'morenews'),
        'before_widget' => '<div id="%1$s" class="widget morenews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="heading-line-before"></span><span class="heading-line">',
        'after_title' => '</span><span class="heading-line-after"></span></h2>',
    ));
    
    register_sidebar(array(
        'name' => __('Main Sidebar', 'morenews'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets for main sidebar.', 'morenews'),
        'before_widget' => '<div id="%1$s" class="widget morenews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="heading-line-before"></span><span class="heading-line">',
        'after_title' => '</span><span class="heading-line-after"></span></h2>',
    ));
    
    register_sidebar(array(
        'name' => __('Front-page Content Section', 'morenews'),
        'id' => 'home-content-widgets',
        'description' => __('Add widgets to front-page contents section.', 'morenews'),
        'before_widget' => '<div id="%1$s" class="widget morenews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="heading-line-before"></span><span class="heading-line">',
        'after_title' => '</span><span class="heading-line-after"></span></h2>',
    ));

    register_sidebar(array(
        'name' => __('Front-page Sidebar Section', 'morenews'),
        'id' => 'home-sidebar-widgets',
        'description' => __('Add widgets to front-page sidebar section.', 'morenews'),
        'before_widget' => '<div id="%1$s" class="widget morenews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="heading-line-before"></span><span class="heading-line">',
        'after_title' => '</span><span class="heading-line-after"></span></h2>',
    ));

    
    
    register_sidebar(array(
        'name' => __('Footer Widget 1', 'morenews'),
        'id' => 'footer-first-widgets-section',
        'description' => __('Displays items on footer first column.', 'morenews'),
        'before_widget' => '<div id="%1$s" class="widget morenews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="heading-line-before"></span><span class="heading-line">',
        'after_title' => '</span><span class="heading-line-after"></span></h2>',
    ));


    register_sidebar(array(
        'name' => __('Footer Widget 2', 'morenews'),
        'id' => 'footer-second-widgets-section',
        'description' => __('Displays items on footer second column.', 'morenews'),
        'before_widget' => '<div id="%1$s" class="widget morenews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="heading-line-before"></span><span class="heading-line">',
        'after_title' => '</span><span class="heading-line-after"></span></h2>',
    ));

    register_sidebar(array(
        'name' => __('Footer Widget 3', 'morenews'),
        'id' => 'footer-third-widgets-section',
        'description' => __('Displays items on footer third column.', 'morenews'),
        'before_widget' => '<div id="%1$s" class="widget morenews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="heading-line-before"></span><span class="heading-line">',
        'after_title' => '</span><span class="heading-line-after"></span></h2>',
    ));



}

add_action('widgets_init', 'morenews_widgets_init');