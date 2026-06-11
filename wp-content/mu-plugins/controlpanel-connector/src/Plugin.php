<?php
/**
 * Control Panel Connector Plugin Container
 *
 * Main plugin class responsible for bootstrapping and managing the lifecycle
 * of all plugin services using a service container pattern.
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
 * Main Plugin class - singleton service container
 *
 * @final
 */
final class Plugin {

    /**
     * Singleton instance
     */
    private static ?self $instance = null;

    /**
     * Registered services
     */
    private array $services = array();

    /**
     * Get singleton instance
     */
    public static function getInstance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Private constructor - use getInstance() instead
     */
    private function __construct() {
    }

    /**
     * Prevent cloning
     */
    private function __clone() {
    }

    /**
     * Bootstrap the plugin by initializing and registering all services
     */
    public function boot(): void {
        add_action(
            'plugins_loaded',
            function () {
                load_muplugin_textdomain(
                    'controlpanel-connector',
                    basename( CONTROLPANEL_CONNECTOR_PLUGIN_DIR ) . '/languages'
                );
            }
        );

        // Register all services
        $this->registerServices();

        // Register hooks for all services
        foreach ( $this->services as $service ) {
            if ( method_exists( $service, 'register' ) ) {
                $service->register();
            }
        }

        // Register admin hooks
        $this->registerAdminHooks();
    }

    /**
     * Register all plugin services
     */
    private function registerServices(): void {
        $this->services['feed_repository'] = new FeedRepository();

        $this->services['blacklist'] = new Blacklist();
        $this->services['settings']  = new Settings();

        $this->services['sso'] = new SSO(
            $this->services['feed_repository']
        );

        // AdminBar needs Settings and FeedRepository
        $this->services['adminbar'] = new AdminBar(
            $this->services['settings'],
            $this->services['feed_repository']
        );

        // Commands service
        $this->services['commands'] = new Commands();
    }

    /**
     * Retrieve a registered service
     *
     * @param string $name Service name
     *
     * @return object|null The service instance or null if not found
     */
    public function getService( string $name ): ?object {
        return $this->services[ $name ] ?? null;
    }

    /**
     * Check if service is registered
     *
     * @param string $name Service name
     *
     * @return bool True if service exists
     */
    public function hasService( string $name ): bool {
        return isset( $this->services[ $name ] );
    }

    /**
     * Get all registered services
     *
     * @return array Array of services
     */
    public function getServices(): array {
        return $this->services;
    }

    public function registerAdminHooks(): void {
        add_filter(
            'plugin_row_meta',
            function ( array $plugin_meta, string $plugin_file, array $plugin_data, string $status ): array {
                if ( $status !== 'mustuse' ) {
                    return $plugin_meta;
                }

                if ( $plugin_file !== 'controlpanel-connector.php' ) {
                    return $plugin_meta;
                }

                /** @var FeedRepository|null $feed_repository */
                $feed_repository = $this->getService( 'feed_repository' );
                if ( ! $feed_repository instanceof FeedRepository ) {
                    return $plugin_meta;
                }
                $feed = $feed_repository->get() ?? array();

                $author_name = $feed['app']['name'] ?? 'PanelAlpha';
                $author_url  = $feed['app']['url'] ?? 'https://panelalpha.com';
                $version     = $plugin_data['Version'] ?? '';

                $plugin_meta = array();
                if ( $version ) {
                    $plugin_meta[] = sprintf(
                        esc_html__( 'Version %s', 'controlpanel-connector' ),
                        esc_html( $version )
                    );
                }
                $plugin_meta[] = sprintf(
                    '%s <a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
                    esc_html__( 'By', 'controlpanel-connector' ),
                    esc_url( $author_url ),
                    esc_html( $author_name )
                );
                return $plugin_meta;
            },
            10,
            4
        );
    }
}
