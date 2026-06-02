<?php
/**
 * Admin Bar Renderer for Control Panel
 *
 * Renders the control panel bar in the WordPress admin and frontend, and enqueues
 * necessary assets (CSS and JavaScript).
 *
 * @package ControlPanelConnector
 * @since 1.5.0
 * @author PanelAlpha
 */

declare(strict_types=1);

namespace ControlPanelConnector;

use ControlPanelConnector\Repository\FeedRepository;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin Bar Renderer class
 */
class AdminBar {

    /**
     * Settings service instance
     */
    private Settings $settings;

    /**
     * Feed repository instance
     */
    private FeedRepository $feedRepository;

    /**
     * Constructor
     *
     * @param Settings       $settings Settings service instance
     * @param FeedRepository $feedRepository Feed repository instance
     */
    public function __construct( Settings $settings, FeedRepository $feedRepository ) {
        $this->settings       = $settings;
        $this->feedRepository = $feedRepository;
    }

    /**
     * Register all admin bar hooks
     */
    public function register(): void {
        // add control panel bar
        add_action( 'wp_before_admin_bar_render', array( $this, 'renderAdminBar' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAssetsForAdminBar' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueFrontendAssets' ) );

        // add button to admin menu
        add_action( 'admin_menu', array( $this, 'addBackToControlPanelMenu' ) );

        // add button to admin bar
        add_action( 'admin_bar_menu', array( $this, 'addControlPanelAdminBarNode' ), 40 );
    }

    /**
     * Render control panel admin bar
     */
    public function renderAdminBar(): void {
        $cpUrl = get_option( 'controlpanel_url' );
        if ( ! $cpUrl ) {
            return;
        }

        if ( ! $this->settings->isBarVisible() ) {
            return;
        }

        $feed = $this->feedRepository->get();
        include CONTROLPANEL_CONNECTOR_PLUGIN_DIR . 'templates/controlpanelbar.phtml';
    }

    /**
     * Enqueue admin bar CSS and JS
     */
    public function enqueueAssetsForAdminBar(): void {
        if ( ! $this->settings->isBarVisible() ) {
            return;
        }

        $this->enqueueBarAssets();
    }

    /**
     * Enqueue assets for frontend
     */
    public function enqueueFrontendAssets(): void {
        if ( is_admin() ) {
            return;
        }

        if ( ! $this->settings->isBarVisible() ) {
            return;
        }

        $this->enqueueBarAssets();
    }

    /**
     * Enqueue bar assets (CSS, JS, and localize script data)
     */
    private function enqueueBarAssets(): void {
        wp_enqueue_style(
            'controlpanel-bar-css',
            CONTROLPANEL_CONNECTOR_PLUGIN_URL . 'assets/css/controlpanelbar.css',
            array(),
            CONTROLPANEL_CONNECTOR__VERSION
        );

        wp_enqueue_script(
            'controlpanel-bar-js',
            CONTROLPANEL_CONNECTOR_PLUGIN_URL . 'assets/js/controlpanelbar.js',
            array(),
            CONTROLPANEL_CONNECTOR__VERSION,
            true
        );

        wp_localize_script(
            'controlpanel-bar-js',
            'controlpanelBarData',
            array(
                'restUrl' => rest_url( 'controlpanel-connector/v1/settings/dismiss-bar' ),
                'nonce'   => wp_create_nonce( 'wp_rest' ),
            )
        );
    }

    /**
     * Add "Back to Control Panel" menu item
     */
    public function addBackToControlPanelMenu(): void {
        $cpUrl = get_option( 'controlpanel_url' );
        if ( ! $cpUrl ) {
            return;
        }

        if ( ! $this->settings->isControlPanelLinkInAdminMenuVisible() ) {
            return;
        }

        $feed = $this->feedRepository->get();

        add_menu_page(
            $feed['back_to_panel_text'],
            $feed['back_to_panel_text'],
            'read',
            $cpUrl,
            '',
            'dashicons-external',
            1
        );
    }

    /**
     * Add Control Panel link node to WordPress Admin Bar
     *
     * @param \WP_Admin_Bar $wp_admin_bar WordPress admin bar instance
     *
     * @return void
     */
    public function addControlPanelAdminBarNode( \WP_Admin_Bar $wp_admin_bar ): void {
        $cpUrl = get_option( 'controlpanel_url' );
        if ( ! $cpUrl ) {
            return;
        }

        if ( ! $this->settings->isControlPanelLinkInAdminBarVisible() ) {
            return;
        }

        $feed  = $this->feedRepository->get();
        $label = esc_html( $feed['back_to_panel_text'] );
        $title = '<span class="ab-icon dashicons dashicons-external" aria-hidden="true"></span><span class="ab-label">' . $label . '</span>';

        $wp_admin_bar->add_node(
            array(
                'id'    => 'controlpanel_admin_bar_link',
                'title' => $title,
                'href'  => esc_url( $cpUrl ),
                'meta'  => array(
                    'target' => '_blank',
                ),
            )
        );
    }
}
