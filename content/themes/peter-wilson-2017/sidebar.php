<?php
/**
 * The theme sidebar.
 *
 * @package   peterwilson-2017
 * @author    Peter Wilson <wilson@peterwilson.cc>
 * @copyright 2017 Peter Wilson
 * @license   GPLv2
 */

if ( is_active_sidebar( 'sidebar-1' ) ) :
	?>
	<aside class="Main_Sidebar">
		<?php dynamic_sidebar( 'sidebar-1' ) ?>
	</aside>
	<?php
endif;
