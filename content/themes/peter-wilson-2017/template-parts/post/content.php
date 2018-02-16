<?php
/**
 * Generic content file for posts.
 *
 * @package   peterwilson-2017
 * @author    Peter Wilson <wilson@peterwilson.cc>
 * @copyright 2017 Peter Wilson
 * @license   GPLv2
 */
?>
<article <?php post_class( 'Article' ) ?>>
	<?php
	if ( is_singular() && get_the_title() ) :
		?>
		<h1 class="Headline Headline-Singular entry-title">
			<?php the_title(); ?>
		</h1>
		<?php
	elseif ( ! is_singular() && get_the_title() ) :
		?>
		<h2 class="Headline Headline-Listing entry-title">
			<a href="<?php the_permalink() ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h2>
		<?php
	endif;
	?>
	<div class="EntryMeta">
		<time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ) ?>">
			<?php the_time( get_option( 'date_format' ) ) ?>
		</time>
	</div>
	<?php the_excerpt() ?>
	<div class="EntryMeta EntryMeta-Footer">
		<?php the_category() ?>
	</div>
</article>
