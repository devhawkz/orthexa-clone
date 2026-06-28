<?php

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'porto_child_enqueue_styles', 1001 );

function porto_child_enqueue_styles() {
	$theme_dir     = get_stylesheet_directory();
	$theme_uri     = get_stylesheet_directory_uri();
	$theme_version = wp_get_theme( get_stylesheet() )->get( 'Version' );

	$style_path = $theme_dir . '/style.css';

	if ( file_exists( $style_path ) ) {
		wp_enqueue_style(
			'porto-child-style',
			$theme_uri . '/style.css',
			array(),
			filemtime( $style_path )
		);
	}

	$header_style_path = $theme_dir . '/assets/css/header.css';

	if ( file_exists( $header_style_path ) ) {
		wp_enqueue_style(
			'porto-child-header',
			$theme_uri . '/assets/css/header.css',
			array( 'porto-child-style' ),
			filemtime( $header_style_path )
		);
	}

	if ( is_rtl() ) {
		$rtl_style_path = $theme_dir . '/style_rtl.css';

		if ( file_exists( $rtl_style_path ) ) {
			wp_enqueue_style(
				'porto-child-rtl',
				$theme_uri . '/style_rtl.css',
				array( 'porto-child-style' ),
				filemtime( $rtl_style_path )
			);
		}
	}
}