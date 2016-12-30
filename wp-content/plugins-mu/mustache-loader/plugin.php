<?php
/*
 * Plugin Name: Load mustache-php
 * Description: Run the mustache-php vendor plugin autoloader.
 * Version:     1.0.0
 * Author:      Peter Wilson
 * Author URI:  https://peterwilson.cc/
 * License:     GPLv2 or later
 */

add_action( 'muplugins_loaded', function () {
	Mustache_Autoloader::register();
});
