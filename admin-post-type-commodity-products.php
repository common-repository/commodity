<?php
/**
 * @package Commodity
 */


/**
 *
 * @since 		1.0.0
 *
 * Creates a custom post type for the products
 *
 */
function commodity_create_post_type() {

	$labels 	= array();
	$args 		= array();

	// Set labels for the custom post type
	$labels = array(
		'name'               => _x( 'Products', 'post type general name' ),
		'singular_name'      => _x( 'Product', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'product' ),
		'add_new_item'       => __( 'Add New Product' ),
		'edit_item'          => __( 'Edit Product' ),
		'new_item'           => __( 'New Product' ),
		'all_items'          => __( 'All Products' ),
		'view_item'          => __( 'View Products' ),
		'search_items'       => __( 'Search Products' ),
		'not_found'          => __( 'No products found' ),
		'not_found_in_trash' => __( 'No products found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Products'
	);

	// Set the arguements for the custom post type
	$args = array(
		'rewrite' 			 	=> array( 'slug' => 'products' ),
		'labels'				=> $labels,
		'description'			=> 'Products',
		'public'				=> true,
		'has_archive'			=> true,
		'exclude_from_search'	=> true,
		'menu_position'			=> 20,
		'menu_icon'				=> 'dashicons-cart',
		'supports'				=> array(
									'title',
									'editor',
									'author',
									'thumbnail',
									'excerpt',
									'custom-fields',
									'revisions',
									'comments',
									'page-attributes'
									)
	);

	// Register the custom post type
	if( get_option('_commodity_show_products_cpt') == 'show' )
	{
		register_post_type( 'commodity_products', $args );
	}


}
add_action( 'init', 'commodity_create_post_type' );


/**
 *
 * @since 		2.1.0
 * @updated 	2.1.1
 *
 * Hide meta boxes by default
 *
 */
function commodity_change_default_hidden( $hidden, $screen ) {
	if ( 'commodity_products' == $screen->id ) {
		$hidden[] 	= 'postcustom';
		$hidden[] 	= 'trackbacksdiv';
		$hidden[] 	= 'commentstatusdiv';
		$hidden[] 	= 'commentsdiv';
		$hidden[] 	= 'slugdiv';
		$hidden[] 	= 'authordiv';
		$hidden[] 	= 'revisionsdiv';
		$hidden[]	= 'pageparentdiv';
	}
	return $hidden;
}
add_filter( 'default_hidden_meta_boxes', 'commodity_change_default_hidden', 10, 2 );



/**
 *
 * @since 		2.0.2
 *
 * Register post thumbnails
 *
 */
function commodity_register_post_thumbnails()
{
	$post_thumbnails = get_theme_support( 'post-thumbnails' );
	$new_post_thumbnails = array();

	if( is_array( $post_thumbnails ) )
	{
		if( is_array( $post_thumbnails[0] ) )
		{
			foreach( $post_thumbnails[0] as $value )
			{
				array_push( $new_post_thumbnails, $value );
			}
		}
	}

	array_push( $new_post_thumbnails, 'commodity_products' );

	// Add support for post thumbnails to the theme
	add_theme_support( 'post-thumbnails', $new_post_thumbnails );
}
add_action( 'after_setup_theme', 'commodity_register_post_thumbnails' );
?>