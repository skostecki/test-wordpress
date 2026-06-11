<?php
/**
 * Plugin Info WP-CLI Command
 *
 * Displays information about the Control Panel Connector plugin.
 *
 * @package ControlPanelConnector\Commands
 * @since 1.5.0
 * @author PanelAlpha
 */

declare(strict_types=1);

namespace ControlPanelConnector\Commands;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Info Command class
 */
class InfoCommand {

	/**
	 * Register the info command with WP-CLI
	 */
	public static function register(): void {
		if ( ! class_exists( 'WP_CLI' ) ) {
			return;
		}

		\WP_CLI::add_command(
			'controlpanel info',
			array( self::class, 'showInfo' ),
			array(
				'shortdesc' => 'Display information about the Control Panel Connector plugin.',
				'synopsis'  => array(),
			)
		);
	}

	/**
	 * Display plugin information via WP-CLI command
	 */
	public static function showInfo(): void {
		if ( ! class_exists( 'WP_CLI' ) ) {
			\WP_CLI::error( 'WP_CLI class is not available.' );
			exit( 1 );
		}

		$info = array(
			'name'    => 'Control Panel Connection',
			'version' => defined( 'CONTROLPANEL_CONNECTOR__VERSION' ) ? CONTROLPANEL_CONNECTOR__VERSION : 'unknown',
			'status'  => 'must-use',
		);

		\WP_CLI::print_value( $info, array( 'format' => 'json' ) );
		exit( 0 );
	}
}
