<?php
/**
 * Child theme search form override.
 */

defined( 'ABSPATH' ) || exit;

$placeholder = function_exists( 'porto_child_get_product_search_placeholder' )
	? porto_child_get_product_search_placeholder()
	: __( 'Pretraživanje proizvoda...', 'porto-child' );
?>

<form method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
		<input class="form-control" placeholder="<?php echo esc_attr( $placeholder ); ?>" name="s" id="s" type="text" value="<?php echo esc_attr( get_search_query( false ) ); ?>">
		<button aria-label="<?php esc_attr_e( 'Search', 'porto' ); ?>" type="submit" class="btn btn-dark p-2"><i class="d-inline-block porto-icon-search-3"></i></button>
	</div>
</form>
