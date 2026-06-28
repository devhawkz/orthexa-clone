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
add_filter( 'gettext', 'porto_child_filter_mini_cart_text', 10, 3 );
add_filter( 'ngettext', 'porto_child_filter_mini_cart_item_count_text', 10, 5 );

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

		foreach ( porto_child_get_translatable_strings() as $name => $string ) {
			pll_register_string(
				$name,
				$string,
				'Porto Child'
			);
		}
	}
}

function porto_child_get_translatable_strings() {
	return array(
		'Mini cart item count singular' => '%d ARTIKL',
		'Mini cart item count few'      => '%d ARTIKLA',
		'Mini cart item count many'     => '%d ARTIKALA',
		'Mini cart view cart'           => 'Pogledaj košaricu',
		'Mini cart empty message'       => 'Nema proizvoda u košarici.',
	);
}

function porto_child_translate_string( $string ) {
	if ( function_exists( 'pll__' ) ) {
		return pll__( $string );
	}

	return $string;
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

function porto_child_filter_mini_cart_text( $translation, $text, $domain ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $translation;
	}

	if ( 'woocommerce' !== $domain ) {
		return $translation;
	}

	$strings = porto_child_get_translatable_strings();

	if ( 'View cart' === $text ) {
		return porto_child_translate_string( $strings['Mini cart view cart'] );
	}

	if ( 'No products in the cart.' === $text ) {
		return porto_child_translate_string( $strings['Mini cart empty message'] );
	}

	return $translation;
}

function porto_child_filter_mini_cart_item_count_text( $translation, $single, $plural, $number, $domain ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $translation;
	}

	if (
		'porto' !== $domain
		|| '%d ITEM' !== $single
		|| '%d ITEMS' !== $plural
	) {
		return $translation;
	}

	$strings = porto_child_get_translatable_strings();
	$key     = 'Mini cart item count many';

	if ( 1 === (int) $number ) {
		$key = 'Mini cart item count singular';
	} elseif ( in_array( (int) $number % 10, array( 2, 3, 4 ), true ) && ! in_array( (int) $number % 100, array( 12, 13, 14 ), true ) ) {
		$key = 'Mini cart item count few';
	}

	return porto_child_translate_string( $strings[ $key ] );
}
