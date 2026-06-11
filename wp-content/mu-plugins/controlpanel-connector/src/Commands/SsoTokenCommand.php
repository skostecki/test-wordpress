<?php
/**
 * SSO Token Generation WP-CLI Command
 *
 * Creates a single sign-on (SSO) token for a user to authorize admin login
 * from the control panel.
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
 * SSO Token Command class
 */
class SsoTokenCommand {

	/**
	 * Register the SSO token command with WP-CLI
	 */
	public static function register(): void {
		if ( ! class_exists( 'WP_CLI' ) ) {
			return;
		}

		\WP_CLI::add_command(
			'controlpanel create_sso_token',
			array( self::class, 'createSsoToken' ),
			array(
				'shortdesc' => 'Creates a single sign-on (SSO) token for a user to authorize admin login from the control panel.',
				'synopsis'  => array(
					array(
						'type'        => 'positional',
						'name'        => 'user_id',
						'description' => 'ID of the user to create SSO token for.',
						'optional'    => false,
						'repeating'   => false,
					),
				),
			)
		);
	}

	/**
	 * Create SSO token for user (WP-CLI command)
	 *
	 * @param array $args Command arguments
	 */
	public static function createSsoToken( array $args ): void {
		if ( ! class_exists( 'WP_CLI' ) ) {
			\WP_CLI::error( 'WP_CLI class is not available.' );
			exit( 1 );
		}

		if ( empty( $args[0] ) ) {
			\WP_CLI::error( 'Missing required argument: user_id.' );
			exit( 1 );
		}

		$userId = $args[0];

		if ( ! is_numeric( $userId ) || (int) $userId <= 0 ) {
			\WP_CLI::error( 'Invalid user_id. Must be a positive integer.' );
			exit( 1 );
		}

		$user = get_user_by( 'id', (int) $userId );
		if ( ! $user ) {
			\WP_CLI::error( 'User not found.' );
			exit( 1 );
		}

		$expires = time() + 60;
		$token   = (string) $expires . '_' . wp_generate_password( 64, false );

		update_user_meta( (int) $userId, 'controlpanel_sso', sha1( $token ) );

		echo json_encode(
			array(
				'token'          => $token,
				'plugin_version' => defined( 'CONTROLPANEL_CONNECTOR__VERSION' ) ? CONTROLPANEL_CONNECTOR__VERSION : 'unknown',
			),
			JSON_UNESCAPED_SLASHES
		);
		exit( 0 );
	}
}
