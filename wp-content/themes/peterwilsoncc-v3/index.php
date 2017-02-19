<?php
wp_head();



// PWCC\Handlebars\get_hb_template_part( 'organism/global/colours' );

var_dump([
	PWCC\Styleguide\get_files(),
	PWCC\Styleguide\get_names(),
]);


wp_footer();
