<?php
/**
 * Settings Manager for Control Panel Bar
 *
 * Manages plugin settings and admin interface for the control panel bar display.
 *
 * @package ControlPanelConnector
 * @since 1.5.0
 * @author PanelAlpha
 */

declare(strict_types=1);

namespace ControlPanelConnector;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings Manager class
 */
class Settings {

	/**
	 * Single option key for all plugin settings
	 */
	private const OPTION_KEY = 'controlpanel_connector_settings';

	/**
	 * Sub-key: bar visibility
	 */
	private const KEY_BAR_VISIBLE = 'bar_visible';

	/**
	 * Sub-key: link in admin menu
	 */
	private const KEY_LINK_IN_ADMIN_MENU = 'link_in_admin_menu';

	/**
	 * Sub-key: link in admin bar
	 */
	private const KEY_LINK_IN_ADMIN_BAR = 'link_in_admin_bar';

	/**
	 * Migration flag option key - set after legacy options are migrated
	 */
	private const MIGRATION_FLAG = 'controlpanel_connector_migrated_v2';

	/**
	 * Legacy option keys (used only for migration)
	 */
	private const LEGACY_OPTION_BAR_VISIBLE        = 'controlpanel_admin_bar_visible';
	private const LEGACY_OPTION_LINK_IN_ADMIN_MENU = 'control_panel_link_in_admin_menu_visible';
	private const LEGACY_OPTION_LINK_IN_ADMIN_BAR  = 'control_panel_link_in_admin_bar_visible';

	/**
	 * Feed option key
	 */
	private const FEED_OPTION = 'controlpanel_feed';

	/**
	 * Feed default setting key for control panel admin bar
	 */
	private const FEED_DEFAULT_SHOW_CONTROL_PANEL_ADMIN_BAR = 'show_control_panel_admin_bar';

	/**
	 * Feed default setting key for link in admin menu
	 */
	private const FEED_DEFAULT_SHOW_LINK_IN_ADMIN_MENU = 'show_link_to_control_panel_in_admin_menu';

	/**
	 * Feed default setting key for link in admin bar
	 */
	private const FEED_DEFAULT_SHOW_LINK_IN_ADMIN_BAR = 'show_link_to_control_panel_in_admin_bar';

	/**
	 * REST API namespace
	 */
	private const REST_NAMESPACE = 'controlpanel-connector/v1';

	/**
	 * Admin menu slug
	 */
	private const MENU_SLUG = 'controlpanel-connector-settings';

	/**
	 * Register all settings and admin menu hooks
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'addSettingsMenu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueSettingsAssets' ) );
		add_action( 'rest_api_init', array( $this, 'registerRestRoutes' ) );
		add_action( 'admin_init', array( $this, 'migrateLegacyOptions' ) );

		add_filter( 'plugin_action_links_controlpanel-connector.php', array( $this, 'addActionLinks' ), 10, 2 );
	}

	/**
	 * Add settings page to admin menu
	 */
	public function addSettingsMenu(): void {
		add_submenu_page(
			'plugins.php',
			__( 'Control Panel Connection', 'controlpanel-connector' ),
			'',
			'manage_options',
			self::MENU_SLUG,
			array( $this, 'renderSettingsPage' )
		);
	}

	/**
	 * Enqueue settings page assets
	 *
	 * @param string $hook_suffix Current admin page hook suffix
	 */
	public function enqueueSettingsAssets( string $hook_suffix ): void {
		if ( $hook_suffix !== 'plugins_page_' . self::MENU_SLUG ) {
			return;
		}

		wp_enqueue_script(
			'controlpanel-settings-js',
			CONTROLPANEL_CONNECTOR_PLUGIN_URL . 'assets/js/settings.js',
			array(),
			CONTROLPANEL_CONNECTOR__VERSION,
			true
		);

		wp_localize_script(
			'controlpanel-settings-js',
			'controlpanelSettingsData',
			array(
				'restUrl' => rest_url( self::REST_NAMESPACE . '/settings' ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
				'i18n'    => array(
					'saved'   => __( 'Settings saved.', 'controlpanel-connector' ),
					'error'   => __( 'An error occurred while saving settings.', 'controlpanel-connector' ),
					'unknown' => __( 'An unexpected error occurred. Please try again.', 'controlpanel-connector' ),
				),
			)
		);
	}

	/**
	 * Register REST API routes
	 */
	public function registerRestRoutes(): void {
		register_rest_route(
			self::REST_NAMESPACE,
			'/settings',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'handleSaveSettings' ),
				'permission_callback' => array( $this, 'checkManageOptionsPermission' ),
				'args'                => array(
					self::KEY_BAR_VISIBLE        => array(
						'type'    => 'boolean',
						'default' => true,
					),
					self::KEY_LINK_IN_ADMIN_MENU => array(
						'type'    => 'boolean',
						'default' => true,
					),
					self::KEY_LINK_IN_ADMIN_BAR  => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE,
			'/settings/dismiss-bar',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'handleDismissBar' ),
				'permission_callback' => array( $this, 'checkManageOptionsPermission' ),
			)
		);
	}

	/**
	 * Permission callback: require manage_options capability
	 *
	 * @return bool|\WP_Error
	 */
	public function checkManageOptionsPermission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error(
				'rest_forbidden',
				__( 'You do not have permission to manage plugin settings.', 'controlpanel-connector' ),
				array( 'status' => 403 )
			);
		}
		return true;
	}

	/**
	 * REST callback: save all settings
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function handleSaveSettings( \WP_REST_Request $request ): \WP_REST_Response {
		$settings = array(
			self::KEY_BAR_VISIBLE        => (bool) $request->get_param( self::KEY_BAR_VISIBLE ),
			self::KEY_LINK_IN_ADMIN_MENU => (bool) $request->get_param( self::KEY_LINK_IN_ADMIN_MENU ),
			self::KEY_LINK_IN_ADMIN_BAR  => (bool) $request->get_param( self::KEY_LINK_IN_ADMIN_BAR ),
		);

		update_option( self::OPTION_KEY, $settings );

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}

	/**
	 * REST callback: dismiss the control panel bar
	 *
	 * @return \WP_REST_Response
	 */
	public function handleDismissBar(): \WP_REST_Response {
		$settings = array_merge(
			$this->getDefaultSettings(),
			$this->getAllSettings(),
			array( self::KEY_BAR_VISIBLE => false )
		);

		update_option( self::OPTION_KEY, $settings );

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}

	/**
	 * Render the settings page
	 */
	public function renderSettingsPage(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'controlpanel-connector' ) );
		}

		$barVisible         = $this->isBarVisible();
		$linkInAdminMenu    = $this->isControlPanelLinkInAdminMenuVisible();
		$linkInAdminBar     = $this->isControlPanelLinkInAdminBarVisible();
		$keyBarVisible      = self::KEY_BAR_VISIBLE;
		$keyLinkInAdminMenu = self::KEY_LINK_IN_ADMIN_MENU;
		$keyLinkInAdminBar  = self::KEY_LINK_IN_ADMIN_BAR;

		include CONTROLPANEL_CONNECTOR_PLUGIN_DIR . 'templates/settings.phtml';
	}

	/**
	 * Check if bar is visible
	 *
	 * @return bool True if bar should be displayed
	 */
	public function isBarVisible(): bool {
		return $this->getSetting(
			self::KEY_BAR_VISIBLE,
			$this->getFeedDefaultSetting( self::FEED_DEFAULT_SHOW_CONTROL_PANEL_ADMIN_BAR, true )
		);
	}

	/**
	 * Check if Control Panel link in admin menu is visible
	 *
	 * @return bool True if link should be displayed
	 */
	public function isControlPanelLinkInAdminMenuVisible(): bool {
		return $this->getSetting(
			self::KEY_LINK_IN_ADMIN_MENU,
			$this->getFeedDefaultSetting( self::FEED_DEFAULT_SHOW_LINK_IN_ADMIN_MENU, true )
		);
	}

	/**
	 * Check if Control Panel link in admin bar is visible
	 *
	 * @return bool True if link should be displayed
	 */
	public function isControlPanelLinkInAdminBarVisible(): bool {
		return $this->getSetting(
			self::KEY_LINK_IN_ADMIN_BAR,
			$this->getFeedDefaultSetting( self::FEED_DEFAULT_SHOW_LINK_IN_ADMIN_BAR, false )
		);
	}

	/**
	 * Cached settings array to avoid repeated get_option calls within one request
	 *
	 * @var array<string, bool>|null
	 */
	private ?array $cachedSettings = null;

	/**
	 * Get all stored settings as array (without defaults applied)
	 *
	 * @return array<string, bool>
	 */
	private function getAllSettings(): array {
		if ( $this->cachedSettings === null ) {
			$stored               = get_option( self::OPTION_KEY, null );
			$this->cachedSettings = is_array( $stored ) ? $stored : array();
		}
		return $this->cachedSettings;
	}

	/**
	 * Get default values for all settings (based on feed defaults and hardcoded fallbacks)
	 *
	 * @return array<string, bool>
	 */
	private function getDefaultSettings(): array {
		return array(
			self::KEY_BAR_VISIBLE        => $this->getFeedDefaultSetting( self::FEED_DEFAULT_SHOW_CONTROL_PANEL_ADMIN_BAR, true ),
			self::KEY_LINK_IN_ADMIN_MENU => $this->getFeedDefaultSetting( self::FEED_DEFAULT_SHOW_LINK_IN_ADMIN_MENU, true ),
			self::KEY_LINK_IN_ADMIN_BAR  => $this->getFeedDefaultSetting( self::FEED_DEFAULT_SHOW_LINK_IN_ADMIN_BAR, false ),
		);
	}

	/**
	 * Get a single setting value with fallback default
	 *
	 * @param string $key     Setting sub-key
	 * @param bool   $default Default value if not set
	 *
	 * @return bool
	 */
	private function getSetting( string $key, bool $default ): bool {
		$stored = $this->getAllSettings();
		if ( ! array_key_exists( $key, $stored ) ) {
			return $default;
		}
		return (bool) $stored[ $key ];
	}

	/**
	 * Get feed default setting value
	 *
	 * @param string $key      Feed default settings key
	 * @param bool   $fallback Fallback value
	 *
	 * @return bool Feed default setting value
	 */
	private function getFeedDefaultSetting( string $key, bool $fallback ): bool {
		$feed = get_option( self::FEED_OPTION, array() );
		if ( ! is_array( $feed ) ) {
			return $fallback;
		}

		$defaultSettings = $feed['default_settings'] ?? null;
		if ( ! is_array( $defaultSettings ) ) {
			return $fallback;
		}

		if ( ! array_key_exists( $key, $defaultSettings ) ) {
			return $fallback;
		}

		return (bool) $defaultSettings[ $key ];
	}

	/**
	 * Migrate legacy individual options to single array option
	 */
	public function migrateLegacyOptions(): void {
		if ( get_option( self::MIGRATION_FLAG ) ) {
			return;
		}

		$legacyBarVisible = get_option( self::LEGACY_OPTION_BAR_VISIBLE, null );
		$legacyAdminMenu  = get_option( self::LEGACY_OPTION_LINK_IN_ADMIN_MENU, null );
		$legacyAdminBar   = get_option( self::LEGACY_OPTION_LINK_IN_ADMIN_BAR, null );

		if ( $legacyBarVisible !== null || $legacyAdminMenu !== null || $legacyAdminBar !== null ) {
			$existing = get_option( self::OPTION_KEY, array() );
			if ( ! is_array( $existing ) ) {
				$existing = array();
			}

			if ( $legacyBarVisible !== null && ! array_key_exists( self::KEY_BAR_VISIBLE, $existing ) ) {
				$existing[ self::KEY_BAR_VISIBLE ] = (bool) $legacyBarVisible;
			}

			if ( $legacyAdminMenu !== null && ! array_key_exists( self::KEY_LINK_IN_ADMIN_MENU, $existing ) ) {
				$existing[ self::KEY_LINK_IN_ADMIN_MENU ] = (bool) $legacyAdminMenu;
			}

			if ( $legacyAdminBar !== null && ! array_key_exists( self::KEY_LINK_IN_ADMIN_BAR, $existing ) ) {
				$existing[ self::KEY_LINK_IN_ADMIN_BAR ] = (bool) $legacyAdminBar;
			}

			update_option( self::OPTION_KEY, $existing );

			delete_option( self::LEGACY_OPTION_BAR_VISIBLE );
			delete_option( self::LEGACY_OPTION_LINK_IN_ADMIN_MENU );
			delete_option( self::LEGACY_OPTION_LINK_IN_ADMIN_BAR );
		}

		add_option( self::MIGRATION_FLAG, true, '', false );
	}

	/**
	 * Add action links for plugin
	 *
	 * @param array  $actions     Plugin action links
	 * @param string $plugin_file Plugin file
	 *
	 * @return array Modified action links
	 */
	public function addActionLinks( array $actions, string $plugin_file ): array {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'plugins.php?page=' . self::MENU_SLUG ),
			__( 'Settings', 'controlpanel-connector' )
		);

		array_unshift( $actions, $settings_link );

		return $actions;
	}
}
