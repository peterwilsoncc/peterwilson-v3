<?php
if ( ! isset( $_ENV['PANTHEON_ENVIRONMENT'] ) || 'live' !== $_ENV['PANTHEON_ENVIRONMENT'] ) {
	if ( file_exists( __DIR__ . '/plugins/query-monitor/wp-content/db.php' ) ) {
		require_once __DIR__ . '/plugins/query-monitor/wp-content/db.php';
	}
}
