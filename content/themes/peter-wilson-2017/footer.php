<?php
/**
 * The theme footer file.
 *
 * @package   peterwilson-2017
 * @author    Peter Wilson <wilson@peterwilson.cc>
 * @copyright 2017 Peter Wilson
 * @license   GPLv2
 */
?>
	<footer class="Page_Footer Footer">
		<?php
		// @codingStandardsIgnoreStart
		// Ignoring to allow spaces for alignment.
		?>
		<svg xmlns="http://www.w3.org/2000/svg" version="1.1"
		     x="0" y="0" viewBox="0 0 160 160"
		     enable-background="new 0 0 160 160"
		     xml:space="preserve" class="Logo"
		     aria-hidden="true" role="img">
			<use href="#Logo" />
		</svg>
		<?php
		// @codingStandardsIgnoreEnd
		?>
		<div class="Widget Widget-Footer Contact">
			<p class="Contact_Name">Peter Wilson</p>
			<p class="Contact_Detail">
				<span class="Ico Ico-Email"></span>
				<a href="mailto:wilson@peterwilson.cc">wilson@peterwilson.cc</a>
			</p>
		</div>
		<?php
		$site_map = wp_nav_menu( [
			'theme_location' => 'footer',
			'container'      => false,
			'menu_class'     => 'SiteMap_List',
			'fallback_cb'    => false,
			'depth'          => 1,
			'echo'           => false,
		] );

		if ( $site_map ) :
			?>
			<nav id="site-map" class="Widget Widget-Footer SiteMap">
				<?php echo $site_map; ?>
			</nav>
			<!--// .SiteMap-->
			<?php
		endif;
		?>
	</footer>
</div>
<!-- // .Page -->
<?php
wp_footer();
?>
</body>
</html>
