<?php
/**
 * Feed Data Repository
 *
 * Manages the control panel feed data stored in WordPress options.
 * Provides a centralized place for feed data default values and retrieval.
 *
 * @package ControlPanelConnector\Repository
 * @since 1.5.0
 * @author PanelAlpha
 */

declare(strict_types=1);

namespace ControlPanelConnector\Repository;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Feed Repository class
 */
class FeedRepository {

    /**
     * Option key for storing feed data
     */
    private const OPTION_KEY = 'controlpanel_feed';

    /**
     * Default feed data structure
     */
    private const DEFAULTS = array(
        'app'                              => array(
            'name' => 'PanelAlpha',
            'url'  => 'https://panelalpha.com',
        ),
        'is_trial'                         => false,
        'trial_expired'                    => false,
        'plan_upgrade_link'                => '',
        'back_to_panel_text'               => 'Back to Control Panel',
        'upgrade_now_text'                 => 'Upgrade Now',
        'message'                          => '',
        'blacklisted_plugins_activation'   => array(),
        'blacklisted_plugins_installation' => array(),
        'blacklisted_themes_installation'  => array(),
        'onboarding'                        => array(
            'enabled'        => false,
            'skip_questions' => false,
            'launch_query'  => '',
        ),
        'default_settings'                 => array(
            'show_control_panel_admin_bar'             => true,
            'show_link_to_control_panel_in_admin_menu' => true,
            'show_link_to_control_panel_in_admin_bar'  => false,
        ),
    );

    /**
     * Get feed data with defaults applied
     *
     * @return array Feed data
     */
    public function get(): array {
        $feed = get_option( self::OPTION_KEY ) ?? array();

        if ( empty( $feed ) ) {
            return self::DEFAULTS;
        }

        // Merge with defaults to ensure all keys exist
        return array_merge( self::DEFAULTS, $feed );
    }

    /**
     * Update feed data
     *
     * @param array $data Feed data to store
     *
     * @return bool True if update was successful
     */
    public function update( array $data ): bool {
        // Sanitize and validate data
        $sanitized = $this->sanitizeData( $data );

        return (bool) update_option( self::OPTION_KEY, $sanitized );
    }

    /**
     * Get default feed data
     *
     * @return array Default feed data
     */
    public function getDefaults(): array {
        return self::DEFAULTS;
    }

    /**
     * Sanitize URL for storage (compatible with WordPress before 5.9)
     *
     * Uses sanitize_url() on WP 5.9+, otherwise esc_url_raw().
     *
     * @param string $url Raw URL
     *
     * @return string Sanitized URL
     */
    private function sanitizeUrl( string $url ): string {
        if ( function_exists( 'sanitize_url' ) ) {
            return sanitize_url( $url );
        }
        return esc_url_raw( $url );
    }

    /**
     * Sanitize and validate feed data
     *
     * @param array $data Raw feed data
     *
     * @return array Sanitized feed data
     */
    private function sanitizeData( array $data ): array {
        $default_settings_input = is_array( $data['default_settings'] ?? null ) ? $data['default_settings'] : array();
        $onboarding_input        = is_array( $data['onboarding'] ?? null ) ? $data['onboarding'] : array();

        return array(
            'app'                              => array(
                'name' => $data['app']['name'] ?? 'PanelAlpha',
                'url'  => $this->sanitizeUrl( $data['app']['url'] ?? 'https://panelalpha.com' ),
            ),
            'is_trial'                         => (bool) ( $data['is_trial'] ?? false ),
            'trial_expired'                    => (bool) ( $data['trial_expired'] ?? false ),
            'plan_upgrade_link'                => $this->sanitizeUrl( $data['plan_upgrade_link'] ?? '' ),
            'back_to_panel_text'               => sanitize_text_field( $data['back_to_panel_text'] ?? 'Back to Control Panel' ),
            'upgrade_now_text'                 => sanitize_text_field( $data['upgrade_now_text'] ?? 'Upgrade Now' ),
            'message'                          => wp_kses_post( $data['message'] ?? '' ),
            'blacklisted_plugins_activation'   => array_map( 'sanitize_text_field', (array) ( $data['blacklisted_plugins_activation'] ?? array() ) ),
            'blacklisted_plugins_installation' => array_map( 'sanitize_text_field', (array) ( $data['blacklisted_plugins_installation'] ?? array() ) ),
            'blacklisted_themes_installation'  => array_map( 'sanitize_text_field', (array) ( $data['blacklisted_themes_installation'] ?? array() ) ),
            'onboarding'                        => array(
                'enabled'        => (bool) ( $onboarding_input['enabled'] ?? false ),
                'skip_questions' => (bool) ( $onboarding_input['skip_questions'] ?? false ),
                'launch_query'  => $onboarding_input['launch_query'] ?? '',
            ),
            'default_settings'                 => array(
                'show_control_panel_admin_bar'             => (bool) ( $default_settings_input['show_control_panel_admin_bar'] ?? true ),
                'show_link_to_control_panel_in_admin_menu' => (bool) ( $default_settings_input['show_link_to_control_panel_in_admin_menu'] ?? true ),
                'show_link_to_control_panel_in_admin_bar'  => (bool) ( $default_settings_input['show_link_to_control_panel_in_admin_bar'] ?? false ),
            ),
        );
    }
}
