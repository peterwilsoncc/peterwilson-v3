<?php

add_action( 'muplugins_loaded', function () {
	Mustache_Autoloader::register();
});
