<?php
/*
 * Plugin Name: Styleguide
 * Description: Display a styleguide within WordPress.
 * Version:     1.0.0-alpha
 * Author:      Peter Wilson
 * Author URI:  https://peterwilson.cc/
 * License:     GPLv2 or later
 */

namespace PWCC\Styleguide;

require_once __DIR__ . '/inc/namespace.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
