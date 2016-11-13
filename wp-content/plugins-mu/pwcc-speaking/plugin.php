<?php
/*
 * Plugin Name: Public speaking content types.
 * Description: Content types required for talks and events.
 * Version:     1.0.0-alpha
 * Author:      Peter Wilson
 * Author URI:  https://peterwilson.cc/
 * License:     GPLv2 or later
 */
namespace PWCC\Speaking;

require_once( __DIR__ . '/namespace.php' );
require_once( __DIR__ . '/inc/presentations.php' );

add_action( 'init', __NAMESPACE__ . '\\init' );
