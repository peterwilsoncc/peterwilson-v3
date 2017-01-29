<?php
wp_head();



// PWCC\Handlebars\get_hb_template_part( 'organism/global/colours' );

var_dump([
	PWCC\Styleguide\get_files(),
	PWCC\Styleguide\get_names(),
	PWCC\Styleguide\get_nav(),
	PWCC\Styleguide\get_pl_config_js(),
]);


wp_footer();
