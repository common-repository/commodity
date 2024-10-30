<?php
/**
 * @package Commodity
 */


/**
 * 
 * @since  		1.0.0
 * 
 * Create a custom taxonomy to add categorise converyor items
 * 
 */
function commodity_create_category_taxonomy() {

	$taxonomy 	= 'commodity_category';
	$labels 	= array();
	$args 		= array();

	// Set labels for the custom taxonomy
	$labels = array(
		'name'              => _x( 'Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Categories' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Category Name' ),
		'menu_name'         => __( 'Categories' )
	);

	// Set the arguements for the custom taxonomy
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);

	register_taxonomy( $taxonomy, array( 'commodity_products' ), $args );
}
add_action( 'init', 'commodity_create_category_taxonomy', 0 );
?>