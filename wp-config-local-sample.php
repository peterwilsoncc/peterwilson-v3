<?php

/**
 * Database config.
 */
defined( 'DB_NAME' )     || define( 'DB_NAME', 'wordpress' );
defined( 'DB_USER' )     || define( 'DB_USER', 'wordpress' );
defined( 'DB_PASSWORD' ) || define( 'DB_PASSWORD', 'vagrantpassword' );
defined( 'DB_HOST' )     || define( 'DB_HOST', 'localhost' );

/**
 * Show errors.
 */
defined( 'WP_DEBUG' )         || define( 'WP_DEBUG', true );
defined( 'WP_DEBUG_DISPLAY' ) || define( 'WP_DEBUG_DISPLAY', true );
defined( 'WP_DEBUG_LOG' )     || define( 'WP_DEBUG_LOG', true );

/**
 * Unsafe.
 */
defined( 'FORCE_SSL_ADMIN' )   || define( 'FORCE_SSL_ADMIN', false );
