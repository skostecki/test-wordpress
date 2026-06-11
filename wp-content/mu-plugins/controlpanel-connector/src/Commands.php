<?php
/**
 * WP-CLI Commands Registrar
 *
 * Registers all WP-CLI commands provided by the plugin.
 *
 * @package ControlPanelConnector
 * @since 1.5.0
 * @author PanelAlpha
 */

declare(strict_types=1);

namespace ControlPanelConnector;

use ControlPanelConnector\Commands\FeedCommand;
use ControlPanelConnector\Commands\SsoTokenCommand;
use ControlPanelConnector\Commands\BlacklistCleanupCommand;
use ControlPanelConnector\Commands\InfoCommand;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Commands Registrar class
 */
class Commands {

	/**
	 * Register all WP-CLI commands
	 */
	public function register(): void {
		FeedCommand::register();
		SsoTokenCommand::register();
		BlacklistCleanupCommand::register();
		InfoCommand::register();
	}
}
