<?php
/**
 * PSR-4 Autoloader for ControlPanelConnector
 *
 * Automatically loads classes from the src/ directory following PSR-4 standard.
 *
 * @package ControlPanelConnector
 * @since 1.5.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

spl_autoload_register(
	static function ( string $class ): void {
		$prefix  = 'ControlPanelConnector\\';
		$baseDir = __DIR__ . '/src/';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relativeClass = substr( $class, $len );
		$file          = $baseDir . str_replace( '\\', '/', $relativeClass ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);
