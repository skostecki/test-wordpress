<?php
/**
 * Blacklist Cleanup WP-CLI Command
 *
 * Cleans up blacklisted plugins and themes by deleting or deactivating them
 * according to the control panel blacklist settings.
 *
 * @package ControlPanelConnector\Commands
 * @since 1.5.0
 * @author PanelAlpha
 */

declare(strict_types=1);

namespace ControlPanelConnector\Commands;

use ControlPanelConnector\Blacklist;
use ControlPanelConnector\Util\SlugExtractor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blacklist Cleanup Command class
 */
class BlacklistCleanupCommand {

	/**
	 * Register the cleanup command with WP-CLI
	 */
	public static function register(): void {
		if ( ! class_exists( 'WP_CLI' ) ) {
			return;
		}

		\WP_CLI::add_command(
			'controlpanel blacklist-cleanup',
			array( self::class, 'cleanup' ),
			array(
				'shortdesc' => 'Cleans up blacklisted plugins and themes by deleting or deactivating them according to the control panel blacklist settings.',
			)
		);
	}

	/**
	 * Run cleanup via WP-CLI command
	 */
	public static function cleanup(): void {
		if ( ! class_exists( 'WP_CLI' ) ) {
			\WP_CLI::error( 'WP_CLI class is not available.' );
			exit( 1 );
		}

		$deleted_plugins     = self::checkInstalledPlugins();
		$deleted_themes      = self::checkInstalledThemes();
		$deactivated_plugins = self::checkActivePlugins();

		$total_count = count( $deleted_plugins ) + count( $deleted_themes ) + count( $deactivated_plugins );

		if ( $total_count === 0 ) {
			\WP_CLI::success( 'No blacklisted items found. Nothing to clean up.' );
			return;
		}

		// Display results in table format
		$results = array();

		foreach ( $deleted_plugins as $plugin ) {
			$results[] = array(
				'type'   => 'Plugin',
				'action' => 'Deleted',
				'name'   => $plugin,
			);
		}

		foreach ( $deleted_themes as $theme ) {
			$results[] = array(
				'type'   => 'Theme',
				'action' => 'Deleted',
				'name'   => $theme,
			);
		}

		foreach ( $deactivated_plugins as $plugin ) {
			$results[] = array(
				'type'   => 'Plugin',
				'action' => 'Deactivated',
				'name'   => $plugin,
			);
		}

		\WP_CLI::line( '' );
		\WP_CLI\Utils\format_items( 'table', $results, array( 'type', 'action', 'name' ) );
		\WP_CLI::line( '' );

		\WP_CLI::success(
			sprintf(
				'Cleanup completed. Total items processed: %d (Deleted plugins: %d, Deleted themes: %d, Deactivated plugins: %d)',
				$total_count,
				count( $deleted_plugins ),
				count( $deleted_themes ),
				count( $deactivated_plugins )
			)
		);
	}

	/**
	 * Check active plugins and deactivate any that became blacklisted
	 *
	 * @return array List of deactivated plugin file paths
	 */
	private static function checkActivePlugins(): array {
		if ( ! function_exists( 'get_option' ) ) {
			return array();
		}

		$active_plugins = get_option( 'active_plugins', array() );
		if ( empty( $active_plugins ) || ! is_array( $active_plugins ) ) {
			return array();
		}

		$blacklist  = new Blacklist();
		$blacklists = $blacklist->getBlacklists();

		if ( ! isset( $blacklists['plugins_activation'] ) || ! is_array( $blacklists['plugins_activation'] ) ) {
			return array();
		}

		$plugins_to_deactivate = array();

		foreach ( $active_plugins as $plugin_file ) {
			if ( ! is_string( $plugin_file ) ) {
				continue;
			}

			$plugin_slug = SlugExtractor::extractPluginSlugFromFile( $plugin_file );
			if ( $plugin_slug && in_array( $plugin_slug, $blacklists['plugins_activation'], true ) ) {
				$plugins_to_deactivate[] = $plugin_file;
			}
		}

		if ( ! empty( $plugins_to_deactivate ) ) {
			if ( ! function_exists( 'deactivate_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			if ( function_exists( 'deactivate_plugins' ) ) {
				deactivate_plugins( $plugins_to_deactivate );
			}
		}

		return $plugins_to_deactivate;
	}

	/**
	 * Check installed plugins and delete any that are blacklisted
	 *
	 * @return array List of deleted plugin file paths
	 */
	private static function checkInstalledPlugins(): array {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			return array();
		}

		$all_plugins = get_plugins();
		if ( empty( $all_plugins ) || ! is_array( $all_plugins ) ) {
			return array();
		}

		$blacklist  = new Blacklist();
		$blacklists = $blacklist->getBlacklists();

		if ( ! isset( $blacklists['plugins_installation'] ) || ! is_array( $blacklists['plugins_installation'] ) ) {
			return array();
		}

		$plugins_to_delete = array();

		foreach ( array_keys( $all_plugins ) as $plugin_file ) {
			if ( ! is_string( $plugin_file ) ) {
				continue;
			}

			$plugin_slug = SlugExtractor::extractPluginSlugFromFile( $plugin_file );

			if ( $plugin_slug && in_array( $plugin_slug, $blacklists['plugins_installation'], true ) ) {
				$plugins_to_delete[] = $plugin_file;
				continue;
			}

			// Special case for Hello Dolly
			if ( 'hello.php' === $plugin_slug && in_array( 'hello-dolly', $blacklists['plugins_installation'], true ) ) {
				$plugins_to_delete[] = $plugin_slug;
			}
		}

		if ( ! empty( $plugins_to_delete ) ) {
			if ( ! function_exists( 'delete_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			if ( function_exists( 'delete_plugins' ) ) {
				delete_plugins( $plugins_to_delete );
			}
		}

		return $plugins_to_delete;
	}

	/**
	 * Check installed themes and delete any that are blacklisted
	 *
	 * @return array List of deleted theme slugs
	 */
	private static function checkInstalledThemes(): array {
		if ( ! function_exists( 'wp_get_themes' ) ) {
			require_once ABSPATH . 'wp-admin/includes/theme.php';
		}

		if ( ! function_exists( 'wp_get_themes' ) ) {
			return array();
		}

		$all_themes = wp_get_themes();
		if ( empty( $all_themes ) || ! is_array( $all_themes ) ) {
			return array();
		}

		$blacklist  = new Blacklist();
		$blacklists = $blacklist->getBlacklists();

		if ( ! isset( $blacklists['themes_installation'] ) || ! is_array( $blacklists['themes_installation'] ) ) {
			return array();
		}

		if ( ! function_exists( 'get_stylesheet' ) ) {
			return array();
		}

		$current_theme    = get_stylesheet();
		$themes_to_delete = array();

		foreach ( $all_themes as $theme_slug => $theme ) {
			if ( ! is_string( $theme_slug ) ) {
				continue;
			}

			if ( in_array( $theme_slug, $blacklists['themes_installation'], true ) ) {
				// Skip current active theme to avoid breaking the site
				if ( $theme_slug === $current_theme ) {
					continue;
				}

				$themes_to_delete[] = $theme_slug;
			}
		}

		if ( ! empty( $themes_to_delete ) ) {
			if ( ! function_exists( 'delete_theme' ) ) {
				require_once ABSPATH . 'wp-admin/includes/theme.php';
			}

			if ( function_exists( 'delete_theme' ) ) {
				foreach ( $themes_to_delete as $theme_slug ) {
					delete_theme( $theme_slug );
				}
			}
		}

		return $themes_to_delete;
	}
}
