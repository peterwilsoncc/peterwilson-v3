<?php
get_header( 'pl' );
?>
<h1>Patterns go here</h1>
<?php
	$organisms = [
		'colours',
	];

	foreach ( $organisms as $organism ) {
		get_extended_template_part( 'pattern-header', '', [], [ 'dir' => 'pattern-library' ] );
		PWCC\Handlebars\get_hb_template_part( 'organisms/' . $organism );
		get_extended_template_part( 'pattern-footer', '', [], [ 'dir' => 'pattern-library' ] );
	}
?>
<?php
get_footer( 'pl' );
