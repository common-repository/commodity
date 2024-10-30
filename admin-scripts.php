<?php
/**
 * @package Commodity
 */

/**
 * 
 * @since  		1.0.0
 * 
 * Add scripts and styles to the admin boxes
 * 
 */
function commodity_enqueue_scripts( $hook ) 
{
	global $post;
	$screens 	= array();
	$post_types = get_post_types( array('public' => true) );
	foreach( $post_types as $post_type)
	{
		if( get_option('_commodity_show_product_values_on_' . $post_type ) === 'show' || get_option('_commodity_show_discounts_on_' . $post_type ) === 'show' )
		{
			array_push( $screens , $post_type );
		}
	}

	if ( $hook == 'post-new.php' || $hook == 'post.php' || $hook == 'settings_page_commodity-settings' ) 
	{
		if ( $hook == 'settings_page_commodity-settings' || in_array( $post->post_type, $screens ) ) 
		{
			// Custom styles
			wp_enqueue_style( 'commodity_admin_styles', plugins_url( 'assets/css/styles.css' , __FILE__ ) );

			// Custom scripts
			wp_enqueue_script( 'commodity_admin_scripts', plugins_url( 'assets/js/scripts.min.js' , __FILE__ ), array( 'jquery' ), '1.0', true );
		
			$commodity_object_values = array( 
				'vat_rate' => get_option('_commodity_global_vat_rate')
			);
    		
    		wp_localize_script( 'commodity_admin_scripts', 'commodity_object', $commodity_object_values );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'commodity_enqueue_scripts' );
?>