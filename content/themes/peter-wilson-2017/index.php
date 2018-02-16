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
	<?php
	switch ( true ) {
		case is_archive() :
			the_archive_title( '<h1 class="SectionHeading">', '</h1>' );
			break;
		case is_home() && ! is_front_page() :
			echo '<p class="SectionHeading">';
			single_post_title();
			echo '</p>';
			break;
		case is_front_page() :
			// Names are names, they don't translate.
			?>
			<p class="SectionHeading">
				Peter Wilson
			</p>
			<?php
			break;
	}
	?>
</div>
<?php
$main_classes = [ 'Page_Main', 'Main' ];

if ( is_active_sidebar( 'sidebar-1' ) ) {
	$sidebar_class  = 'has-Sidebar';
	$main_classes[] = $sidebar_class;
}
?>
<main class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $main_classes ) ) ?>">
	<div class="Main_Body <?php echo sanitize_html_class( $sidebar_class ) ?>">
		<?php
		while ( have_posts() ) :
			the_post();
			get_extended_template_part( 'post/content', get_post_format() );
		endwhile;
		?>

	</div>
	<?php get_sidebar(); ?>
</main>
<?php
get_footer();
