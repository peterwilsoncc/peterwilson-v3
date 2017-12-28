<?php
/**
 * The theme index file.
 *
 * @package   peterwilson-2017
 * @author    Peter Wilson <wilson@peterwilson.cc>
 * @copyright 2017 Peter Wilson
 * @license   GPLv2
 */

get_header();
?>
<div class="Page_SectionLead">
</div>
<?php
$main_classes = [ 'Page_Main', 'Main' ];
if ( is_active_sidebar( 'sidebar-1' ) ) {
	$main_classes[] = 'has-Sidebar';
}
?>
<main class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $main_classes ) ) ?>">
	<div class="Main_Body">

	</div>
	<?php get_sidebar(); ?>
</main>
<?php
get_footer();
