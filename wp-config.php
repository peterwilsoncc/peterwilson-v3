<?php

/**
 * Use an uncommitted local config file when not on Pantheon.
 */

if ( ! isset( $_ENV['PANTHEON_ENVIRONMENT'] ) && file_exists( __DIR__ . '/wp-config-local.php' ) ) {
	// Local config, not on Patheon.
	require_once( __DIR__ . '/wp-config-local.php' );
} elseif ( isset( $_ENV['PANTHEON_ENVIRONMENT'] ) ) {
	// On Pantheon.
	/**
	 * @TODO: configure Pantheon.
	 */
} else {
	// No local config, no Pantheon.
	// I'm dead.
	exit( 'No WordPress config found' );
}

/**
 * Standard WordPress configuration.
 */

// Database settings.
$table_prefix  = 'wp_';
defined( 'DB_CHARSET' ) || define( 'DB_CHARSET', 'utf8' );
defined( 'DB_COLLATE' ) || define( 'DB_COLLATE', '' );

// Language.
defined( 'WPLANG' ) || define( 'WPLANG', '' );

/**
 * Hide errors.
 *
 * Unless the local-config file says otherwise.
 */
defined( 'WP_DEBUG' )         || define( 'WP_DEBUG', false );
defined( 'WP_DEBUG_DISPLAY' ) || define( 'WP_DEBUG_DISPLAY', false );
defined( 'WP_DEBUG_LOG' )     || define( 'WP_DEBUG_LOG', false );

/**
 * Miscellaneous config settings.
 */
defined( 'WP_POST_REVISIONS' ) || define( 'WP_POST_REVISIONS', 3 );
defined( 'FORCE_SSL_ADMIN' )   || define( 'FORCE_SSL_ADMIN', true );

/* That's all, stop editing! Happy Pressing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/wp/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
