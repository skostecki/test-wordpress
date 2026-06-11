<?php
/**
 * Control Panel Connection
 *
 * Must-use plugin for PanelAlpha control panel integration.
 *
 * @package ControlPanelConnector
 * @controlpanel-connector
 * Plugin Name:       Control Panel Connection
 * Description:       This utility plugin is used by our control panel for various tasks, such as authenticating you as an admin when logging in through the WP Admin button, or helping you return to the control panel.
 * Version:           1.7.1
 * Requires PHP:      7.4
 * Author:            PanelAlpha
 * Author URI:        https://panelalpha.com
 * Text Domain:       controlpanel-connector
 */

declare(strict_types=1);

namespace ControlPanelConnector;

if (!defined('ABSPATH')) {
    exit;
}

define('CONTROLPANEL_CONNECTOR__VERSION', '1.7.1');
define('CONTROLPANEL_CONNECTOR_FILE', __FILE__);
define('CONTROLPANEL_CONNECTOR_DIR', __DIR__);
define('CONTROLPANEL_CONNECTOR_PLUGIN_DIR', __DIR__ . '/controlpanel-connector/');
define('CONTROLPANEL_CONNECTOR_PLUGIN_URL', plugins_url('controlpanel-connector/', __FILE__));

require_once CONTROLPANEL_CONNECTOR_PLUGIN_DIR . 'autoload.php';

Plugin::getInstance()->boot();
