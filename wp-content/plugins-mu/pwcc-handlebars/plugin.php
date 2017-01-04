<?php
/*
 * Plugin Name: Handlebars templating
 * Description: Display a handlebars template in WP.
 * Version:     1.0.0-alpha
 * Author:      Peter Wilson
 * Author URI:  https://peterwilson.cc/
 * License:     GPLv2 or later
 */

namespace PWCC\Handlebars;

require_once __DIR__ . '/inc/namespace.php';
require_once __DIR__ . '/inc/class-template-part.php';
require_once __DIR__ . '/inc/helpers/namespace.php';
require_once __DIR__ . '/inc/renderapidata/namespace.php';

add_action( 'muplugins_loaded', __NAMESPACE__ . '\\bootstrap' );
