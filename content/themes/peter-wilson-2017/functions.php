<?php
/**
 * @package peterwilson-2017
 * @author Peter Wilson <wilson@peterwilson.cc>
 * @copyright 2017 Peter Wilson
 * @license GPLv2
 * @version 1.0.0-alpha
 */

namespace PWCC\PeterWilson2017;

include __DIR__ . '/inc/namespace.php';

add_action( 'after_setup_theme', __NAMESPACE__ . '\\bootstrap' );
