<?php
// @codingStandardsIgnoreFile
$_SERVER['HTTP_HOST'] = 'localhost';

define( 'HM_ENV', 'travisci' );

// Define different environment variables for testing.
define( 'DB_HOST', 'localhost' );
if ( file_exists( '/chassis' ) ) {
	define( 'DB_NAME', 'wordpress' );
	define( 'DB_USER', 'wordpress' );
	define( 'DB_PASSWORD', 'vagrantpassword' );
} else {
	define( 'DB_NAME', 'wordpress_test' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASS', '' );
	define( 'DB_PASSWORD', '' );
}
$table_prefix = 'wptests_';

define( 'WP_TESTS_DOMAIN', 'localhost' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );

define( 'WP_PHP_BINARY', 'php' );

define( 'WP_TESTS_MULTISITE', false );

define( 'HM_DEV', true );



// Define Site URL: WordPress in a subdirectory.
if ( ! defined( 'WP_SITEURL' ) ) {
	define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp' );
}

// Define Home URL
if ( ! defined( 'WP_HOME' ) ) {
	define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );
}

// Define path & url for Content
define( 'WP_CONTENT_DIR', dirname( dirname( __DIR__ ) ) . '/content' );
define( 'WP_CONTENT_URL', WP_HOME . '/content' );

// Prevent editing of files through the admin.
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true );

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( dirname( __DIR__ ) ) . '/wp/' );
}
