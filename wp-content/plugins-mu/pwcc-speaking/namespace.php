<?php
namespace PWCC\Speaking;

function init() {
	if ( ! function_exists( 'register_extended_post_type' ) ) {
		// Dependency not meet.
		return;
	}

	Presentations\init();
}
