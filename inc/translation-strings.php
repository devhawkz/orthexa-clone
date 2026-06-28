<?php
/**
 * Translation-ready strings and Polylang registrations.
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'PORTO_CHILD_PRODUCT_SEARCH_PLACEHOLDER' ) ) {
	define( 'PORTO_CHILD_PRODUCT_SEARCH_PLACEHOLDER', 'Pretraživanje proizvoda...' );
}

add_action( 'after_setup_theme', 'porto_child_load_textdomain' );
add_action( 'init', 'porto_child_register_polylang_strings' );
add_action( 'wp', 'porto_child_set_porto_search_placeholder', 20 );
add_filter( 'porto_search_form_content', 'porto_child_filter_search_form_placeholder', 10, 2 );
add_filter( 'option_yith_wcas_search_input_label', 'porto_child_filter_yith_search_placeholder' );

function porto_child_load_textdomain() {
	load_child_theme_textdomain( 'porto-child', get_stylesheet_directory() . '/languages' );
}

function porto_child_register_polylang_strings() {
	if ( function_exists( 'pll_register_string' ) ) {
		pll_register_string(
			'Product search placeholder',
			PORTO_CHILD_PRODUCT_SEARCH_PLACEHOLDER,
			'Porto Child'
		);
	}
}

function porto_child_get_product_search_placeholder() {
	if ( function_exists( 'pll__' ) ) {
		return pll__( PORTO_CHILD_PRODUCT_SEARCH_PLACEHOLDER );
	}

	return __( 'Pretraživanje proizvoda...', 'porto-child' );
}

function porto_child_set_porto_search_placeholder() {
	global $porto_settings;

	if ( is_array( $porto_settings ) ) {
		$porto_settings['search-placeholder'] = porto_child_get_product_search_placeholder();
	}
}

function porto_child_filter_search_form_placeholder( $search_form_content, $is_mobile ) {
	unset( $is_mobile );

	if ( '' === $search_form_content || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $search_form_content;
	}

	$processor   = new WP_HTML_Tag_Processor( $search_form_content );
	$placeholder = porto_child_get_product_search_placeholder();

	while ( $processor->next_tag( 'input' ) ) {
		if ( 's' === $processor->get_attribute( 'name' ) ) {
			$processor->set_attribute( 'placeholder', $placeholder );
			break;
		}
	}

	return $processor->get_updated_html();
}

function porto_child_filter_yith_search_placeholder( $placeholder ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $placeholder;
	}

	return porto_child_get_product_search_placeholder();
}
