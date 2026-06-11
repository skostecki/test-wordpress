<?php
/**
 * Feed Update WP-CLI Command
 *
 * Updates the control panel feed data with provided JSON payload containing
 * trial status, upgrade links, messages, and blacklists.
 *
 * @package ControlPanelConnector\Commands
 * @since 1.5.0
 * @author PanelAlpha
 */

declare(strict_types=1);

namespace ControlPanelConnector\Commands;

use ControlPanelConnector\Repository\FeedRepository;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Feed Command class
 */
class FeedCommand {

    /**
     * Kept as a string here to avoid coupling to Settings internals.
     */
    private const SETTINGS_OPTION_KEY = 'controlpanel_connector_settings';

    /**
     * Settings sub-keys.
     */
    private const KEY_BAR_VISIBLE        = 'bar_visible';
    private const KEY_LINK_IN_ADMIN_MENU = 'link_in_admin_menu';
    private const KEY_LINK_IN_ADMIN_BAR  = 'link_in_admin_bar';

    /**
     * Register the feed command with WP-CLI
     */
    public static function register(): void {
        if ( ! class_exists( 'WP_CLI' ) ) {
            return;
        }

        \WP_CLI::add_command(
            'controlpanel feed',
            array( self::class, 'updateFeed' ),
            array(
                'shortdesc' => 'Updates the control panel feed data with the provided JSON payload, including trial status, upgrade links, messages, and blacklists for plugins and themes.',
                'synopsis'  => array(
                    array(
                        'type'        => 'positional',
                        'name'        => 'data',
                        'description' => 'JSON-encoded object contain data such as trial status, upgrade links, messages, and blacklists for plugins and themes.',
                        'optional'    => false,
                        'repeating'   => false,
                    ),
                    array(
                        'type'        => 'flag',
                        'name'        => 'apply-default-settings',
                        'description' => 'Apply feed default_settings to WordPress options, overriding existing user settings for bar/link visibility.',
                        'optional'    => true,
                    ),
                ),
            )
        );
    }

    /**
     * Update feed via WP-CLI command
     *
     * @param array $args       Command positional arguments
     * @param array $assoc_args Command associative arguments (flags/options)
     */
    public static function updateFeed( array $args, array $assoc_args = array() ): void {
        if ( ! class_exists( 'WP_CLI' ) ) {
            \WP_CLI::error( 'WP_CLI class is not available.' );
            exit( 1 );
        }

        if ( empty( $args[0] ) ) {
            \WP_CLI::error( 'Missing required argument: data (JSON payload).' );
            exit( 1 );
        }

        $feed = json_decode( $args[0], true );
        if ( json_last_error() !== JSON_ERROR_NONE ) {
            \WP_CLI::error( 'Invalid JSON provided in data argument: ' . json_last_error_msg() );
            exit( 1 );
        }

        if ( ! is_array( $feed ) ) {
            \WP_CLI::error( 'Decoded JSON data must be an object.' );
            exit( 1 );
        }

        $repository = new FeedRepository();
        $repository->update( $feed );

        $shouldApplyDefaultSettings = ! empty( $assoc_args['apply-default-settings'] );
        if ( $shouldApplyDefaultSettings ) {
            $defaultSettings = $feed['default_settings'] ?? null;
            if ( ! is_array( $defaultSettings ) ) {
                \WP_CLI::error( 'Flag --apply-default-settings requires "default_settings" object in the feed payload.' );
                exit( 1 );
            }

            $settings = array(
                self::KEY_BAR_VISIBLE        => (bool) ( $defaultSettings['show_control_panel_admin_bar'] ?? true ),
                self::KEY_LINK_IN_ADMIN_MENU => (bool) ( $defaultSettings['show_link_to_control_panel_in_admin_menu'] ?? true ),
                self::KEY_LINK_IN_ADMIN_BAR  => (bool) ( $defaultSettings['show_link_to_control_panel_in_admin_bar'] ?? false ),
            );

            update_option( self::SETTINGS_OPTION_KEY, $settings );
        }

        // Handle side effects based on feed data
        $onboardingOptions = is_array( $feed['onboarding'] ?? null ) ? $feed['onboarding'] : array();
        if ( ! empty( $onboardingOptions['enabled'] ) && ! empty( $onboardingOptions['skip_questions'] ) ) {
            self::skipExtendifyQuestions();
        }

        if ( ! empty( $feed['is_trial'] ) ) {
            self::showAdminBar();
        }

        \WP_CLI::success( 'Feed updated successfully.' );
        exit( 0 );
    }

    /**
     * Show admin bar when service is trial
     */
    private static function showAdminBar(): void {
        update_option( 'controlpanel_admin_bar_visible', true );
    }

    /**
     * Skip Extendify onboarding questions
     */
    private static function skipExtendifyQuestions(): void {
        $option = get_option( 'extendify_launch_loaded' ) ?? false;
        if ( empty( $option ) ) {
            update_option( 'extendify_launch_loaded', date( 'Y-m-d H:i:s' ) );
        }
    }
}
