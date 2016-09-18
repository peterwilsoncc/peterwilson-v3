<?php

$_SERVER['HTTP_HOST'] = 'localhost';

if ( isset( $_SERVER['TRAVIS'] ) && 'true' === $_SERVER['TRAVIS'] ) {
	// Travis CI environment.
	define( 'DB_NAME', 'wordpress_test' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', '' );
	define( 'DB_HOST', 'localhost' );
} else {
	// Dev environment (probably).
	define( 'DB_NAME', 'wordpress_test' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', 'password' );
	define( 'DB_HOST', 'localhost' );
}

$table_prefix = 'wptests_';

define( 'WP_TESTS_DOMAIN', 'localhost' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );

define( 'WP_PHP_BINARY', 'php' );

// Define Site URL: WordPress in a subdirectory.
if ( ! defined( 'WP_SITEURL' ) )
	define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/' );

// Define Home URL
if ( ! defined( 'WP_HOME' ) )
	define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );

// Prevent editing of files through the admin.
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true );


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname(__FILE__) . '/../../' );

// include_once( __DIR__ . '/../wp-config.php' );
