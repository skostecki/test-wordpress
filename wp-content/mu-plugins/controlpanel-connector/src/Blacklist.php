<?php
/**
 * Plugin and Theme Blacklist Manager
 *
 * Manages blacklisting of plugins and themes, preventing installation
 * and activation of restricted items.
 *
 * @package ControlPanelConnector
 * @since 1.5.0
 * @author PanelAlpha
 */

declare(strict_types=1);

namespace ControlPanelConnector;

use ControlPanelConnector\Util\SlugExtractor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blacklist Manager class
 */
class Blacklist {

	/**
	 * Register all blacklist hooks
	 */
	public function register(): void {
		// Hide plugin activation link in WP Admin
		add_filter( 'plugin_action_links', array( $this, 'removeActivationLink' ), 10, 2 );

		// Block plugin activation
		add_action( 'activate_plugin', array( $this, 'blockPluginActivation' ), 10, 2 );

		// Block plugin and theme installation
		add_filter( 'upgrader_source_selection', array( $this, 'blockInstallationAfterDownload' ), 10, 4 );

		// Filter blacklisted plugins and themes
		add_filter( 'plugins_api_result', array( $this, 'filterPluginSearchResults' ), 10, 3 );
		add_filter( 'themes_api_result', array( $this, 'filterThemeSearchResults' ), 10, 3 );
	}

	/**
	 * Get blacklist data from options
	 *
	 * @return array Blacklist configuration
	 */
	public function getBlacklists(): array {
		$feed = get_option( 'controlpanel_feed', array() );
		return array(
			'plugins_installation' => $feed['blacklisted_plugins_installation'] ?? array(),
			'plugins_activation'   => $feed['blacklisted_plugins_activation'] ?? array(),
			'themes_installation'  => $feed['blacklisted_themes_installation'] ?? array(),
		);
	}

	/**
	 * Remove activation links for blacklisted plugins
	 *
	 * @param array  $actions Plugin action links
	 * @param string $plugin_file Plugin file path
	 *
	 * @return array Modified action links
	 */
	public function removeActivationLink( array $actions, string $plugin_file ): array {
		$plugin_slug = $this->extractPluginSlugFromFile( $plugin_file );

		if ( $plugin_slug ) {
			$blacklists = $this->getBlacklists();
			if ( in_array( $plugin_slug, $blacklists['plugins_activation'], true ) ) {
				unset( $actions['activate'] );
			}
		}

		return $actions;
	}

	/**
	 * Block plugin and theme installations after download but before actual installation
	 *
	 * NOTE: $source must NOT be type-hinted as string - WordPress may pass a WP_Error
	 * object here if a previous filter in the chain returned one. Declaring string $source
	 * with strict_types=1 would cause a fatal TypeError in that scenario.
	 *
	 * @param string|\WP_Error $source Installation source path, or WP_Error from prior filter
	 * @param string           $remote_source Remote source path
	 * @param object           $upgrader Upgrader object
	 * @param array            $hook_extra Additional hook data
	 *
	 * @return string|\WP_Error Source path or error
	 */
	public function blockInstallationAfterDownload(
		$source,
		string $remote_source,
		object $upgrader,
		array $hook_extra
	) {
		// Pass through WP_Error from a previous filter unchanged
		if ( is_wp_error( $source ) ) {
			return $source;
		}

		if ( ! isset( $hook_extra['action'] ) || 'install' !== $hook_extra['action'] ) {
			return $source;
		}

		if ( ! isset( $hook_extra['type'] ) || ! in_array( $hook_extra['type'], array( 'plugin', 'theme' ), true ) ) {
			return $source;
		}

		$type       = $hook_extra['type'];
		$slug       = basename( rtrim( $source, '/' ) );
		$blacklists = $this->getBlacklists();

		if ( 'plugin' === $type && in_array( $slug, $blacklists['plugins_installation'], true ) ) {
			return new \WP_Error(
				'plugin_blacklisted',
				sprintf( 'Plugin "%s" is blacklisted and cannot be installed.', $slug )
			);
		}

		if ( 'theme' === $type && in_array( $slug, $blacklists['themes_installation'], true ) ) {
			return new \WP_Error(
				'theme_blacklisted',
				sprintf( 'Theme "%s" is blacklisted and cannot be installed.', $slug )
			);
		}

		return $source;
	}

	/**
	 * Block plugin activation via both WP-CLI and admin UI
	 *
	 * @param string   $plugin Plugin file path
	 * @param bool|null $network_wide Whether activation is network-wide (null when not provided)
	 *
	 * @return void
	 */
	public function blockPluginActivation( string $plugin, ?bool $network_wide ): void {
		$slug = $this->extractPluginSlugFromFile( $plugin );

		if ( ! $slug ) {
			return;
		}

		$blacklists = $this->getBlacklists();

		if ( in_array( $slug, $blacklists['plugins_activation'], true ) ) {
			wp_die(
				sprintf( 'Plugin "%s" is blacklisted and cannot be activated.', $slug ),
				'plugin_blacklisted',
				array( 'back_link' => true )
			);
		}
	}

	/**
	 * Filter plugin search results to hide blacklisted plugins
	 *
	 * @param object|\WP_Error     $result API result object or error
	 * @param string               $action Action being performed
	 * @param object|array         $args   Additional arguments
	 *
	 * @return object|\WP_Error Modified result or unchanged error
	 */
	public function filterPluginSearchResults( $result, string $action, $args ) {
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		if ( ! isset( $result->plugins ) || ! is_array( $result->plugins ) ) {
			return $result;
		}

		$blacklists       = $this->getBlacklists();
		$filtered_plugins = array();

		foreach ( $result->plugins as $plugin ) {
			if ( ! in_array( $plugin['slug'], $blacklists['plugins_installation'], true ) ) {
				$filtered_plugins[] = $plugin;
			}
		}

		$result->plugins         = $filtered_plugins;
		$result->info['results'] = count( $filtered_plugins );

		return $result;
	}

	/**
	 * Filter theme search results to hide blacklisted themes
	 *
	 * @param array|object|\WP_Error $result API result (array, object, or error)
	 * @param string                 $action Action being performed
	 * @param object|array           $args   Additional arguments
	 *
	 * @return array|object Modified result or unchanged value/error
	 */
	public function filterThemeSearchResults( $result, string $action, $args ) {
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		if ( is_array( $result ) ) {
			return $result;
		}
		if ( ! isset( $result->themes ) || ! is_array( $result->themes ) ) {
			return $result;
		}

		$blacklists      = $this->getBlacklists();
		$filtered_themes = array();

		foreach ( $result->themes as $theme ) {
			if ( ! in_array( $theme->slug, $blacklists['themes_installation'], true ) ) {
				$filtered_themes[] = $theme;
			}
		}

		$result->themes          = $filtered_themes;
		$result->info['results'] = count( $filtered_themes );

		return $result;
	}

	/**
	 * Extract plugin slug from plugin file path
	 *
	 * @param string $plugin_file Plugin file path
	 *
	 * @return string|null Plugin slug or null if not found
	 */
	private function extractPluginSlugFromFile( string $plugin_file ): ?string {
		return SlugExtractor::extractPluginSlugFromFile( $plugin_file );
	}
}
