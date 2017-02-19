<html>
<head>
	<title>Pattern Library</title>
	<?php wp_head() ?>
</head>
<body <?php body_class() ?>>
	<h1>Patterns go here</h1>
	<?php
		$organisms = [
			'colours',
		];

		foreach ( $organisms as $organism ) {
			PWCC\Handlebars\get_hb_template_part( 'organisms/' . $organism );
		}
	?>
	<?php wp_footer() ?>
</body>
