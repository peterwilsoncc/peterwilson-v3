<?php
/**
 * The theme post/page file.
 *
 * @package   peterwilson-2017
 * @author    Peter Wilson <wilson@peterwilson.cc>
 * @copyright 2017 Peter Wilson
 * @license   GPLv2
 */

get_header();
/*
 * Get the post if it's set.
 *
 * I'm not doing anything special here if it doesn't because it should, OK,
 * it just should. If it doesn't and execution reaches this file we have much
 * bigger problems. OK? OK!
 */
have_posts() && the_post();
?>
<div class="Page_SectionLead">
	<?php
	if ( is_singular( 'post' ) ) :
		?>
		<p class="SectionHeading"><?php esc_html_e( 'Blog', 'pwcc' ) ?></p>
		<?php
	else :
		?>
		<h1 class="SectionHeading entry-title"><?php single_post_title() ?></h1>
		<?php
	endif;
	?>
</div>
<?php
$custom_post_classes = [
	'Page_Main',
	'Main',
	'Article',
];
if ( is_active_sidebar( 'sidebar-1' ) ) {
	$custom_post_classes[] = 'has-Sidebar';
}
?>
<main <?php post_class( $custom_post_classes ); ?>>
	<div class="Main_Lead Article_Lead">
		<?php
		if ( is_singular( 'post' ) ) :
			?>
			<h1 class="Headline entry-title">
				<?php single_post_title(); ?>
			</h1>
			<?php
		endif;
		?>
	</div>
	<div class="Main_Body Article_Body entry-content">
		<?php the_content(); ?>
	</div>
	<?php get_sidebar(); ?>
</main>
<div class="Page_SectionFollow">
</div>
<?php
get_footer();
