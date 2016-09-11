<?php

defined( 'DB_NAME' )     || define( 'DB_NAME', 'database_name_here' );
defined( 'DB_USER' )     || define( 'DB_USER', 'root' );
defined( 'DB_PASSWORD' ) || define( 'DB_PASSWORD', '' );
defined( 'DB_HOST' )     || define( 'DB_HOST', 'localhost' );

defined( 'WP_SITEURL' )   || define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp' ); // @codingStandardsIgnoreLine
defined( 'WP_HOME' )     || define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST']; // @codingStandardsIgnoreLine

/**
 * Show errors.
 */
defined( 'WP_DEBUG' )         || define( 'WP_DEBUG', true );
defined( 'WP_DEBUG_DISPLAY' ) || define( 'WP_DEBUG_DISPLAY', true );
defined( 'WP_DEBUG_LOG' )     || define( 'WP_DEBUG_LOG', true );
