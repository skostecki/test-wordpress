<?php
/**
 * Slug Extractor Utility
 *
 * Utility class for extracting plugin and theme slugs from various sources
 * (file paths, URLs, zip files, etc.).
 *
 * @package ControlPanelConnector\Util
 * @since 1.5.0
 * @author PanelAlpha
 */

declare(strict_types=1);

namespace ControlPanelConnector\Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slug Extractor class
 */
class SlugExtractor {

	/**
	 * Extract plugin slug from plugin file path
	 *
	 * Examples:
	 * - "hello-dolly/hello.php" => "hello-dolly"
	 * - "hello.php" => "hello.php"
	 *
	 * @param string $plugin_file Plugin file path
	 *
	 * @return string|null Plugin slug or null if not found
	 */
	public static function extractPluginSlugFromFile( string $plugin_file ): ?string {
		if ( strpos( $plugin_file, '/' ) !== false ) {
			$parts = explode( '/', $plugin_file );
			return isset( $parts[0] ) ? $parts[0] : null;
		}
		return $plugin_file;
	}

	/**
	 * Extract plugin slug from various installation sources
	 *
	 * Supports:
	 * - URLs: https://downloads.wordpress.org/plugins/hello-dolly.zip
	 * - Zip files: /path/to/hello-dolly-1.0.0.zip
	 * - Simple slugs: hello-dolly
	 *
	 * @param string $source Installation source (URL, file path, or slug)
	 *
	 * @return string|null Plugin slug or null if not extractable
	 */
	public static function extractPluginSlug( string $source ): ?string {
		// Handle URLs
		if ( filter_var( $source, FILTER_VALIDATE_URL ) ) {
			$path = parse_url( $source, PHP_URL_PATH );
			if ( is_string( $path ) && preg_match( '#/plugins/([^/]+)/#', $path, $matches ) ) {
				return $matches[1];
			}
		}

		// Handle zip files with versions (e.g., plugin-name-1.0.0.zip)
		if ( preg_match( '/([^\/\\\\]+)\.zip$/i', $source, $matches ) ) {
			$filename = $matches[1];
			// Remove version suffix (e.g., -1.0.0)
			$slug = preg_replace( '/(-\d+(?:\.\d+)*.*)?$/', '', $filename );
			return $slug;
		}

		// Handle simple slugs
		$source = trim( $source, '/' );
		if ( strpos( $source, '/' ) === false ) {
			return $source;
		}

		return null;
	}

	/**
	 * Extract theme slug from various installation sources
	 *
	 * Supports:
	 * - URLs: https://downloads.wordpress.org/themes/twentytwentythree.zip
	 * - Zip files: /path/to/twentytwentythree-1.0.0.zip
	 * - Simple slugs: twentytwentythree
	 *
	 * @param string $source Installation source (URL, file path, or slug)
	 *
	 * @return string|null Theme slug or null if not extractable
	 */
	public static function extractThemeSlug( string $source ): ?string {
		// Handle URLs
		if ( filter_var( $source, FILTER_VALIDATE_URL ) ) {
			$path = parse_url( $source, PHP_URL_PATH );
			if ( is_string( $path ) && preg_match( '#/themes/([^/]+)/#', $path, $matches ) ) {
				return $matches[1];
			}
		}

		// Handle zip files with versions (e.g., theme-name-1.0.0.zip)
		if ( preg_match( '/([^\/\\\\]+)\.zip$/i', $source, $matches ) ) {
			$filename = $matches[1];
			// Remove version suffix (e.g., -1.0.0)
			$slug = preg_replace( '/(-\d+(?:\.\d+)*.*)?$/', '', $filename );
			return $slug;
		}

		// Handle simple slugs
		$source = trim( $source, '/' );
		if ( strpos( $source, '/' ) === false ) {
			return $source;
		}

		return null;
	}
}
