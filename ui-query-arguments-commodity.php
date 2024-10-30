<?php
/**
 * @package Commodity
 */


function commodity_query_arguements( $args = array() ) 
{
	$defaults = array(
		'featured'					=> false, 							// [ true | false ] - Set to true to return posts that have the featured post custom meta data set to true
		'featured_post_meta_key' 	=> '_commodity_featured',			// The custom meta field that identifies the featured post, will also accept an array
		'order'						=> 'ASC',							// [ ASC | DESC ]
		'orderby'					=> 'date', 							// [ date | menu_order | title ]
		'posts_per_page'			=> 5,								// Set number of posts to return, -1 will return all
		'post_type'					=> 'commodity_products',			// [ post | page | custom post type | array() ]			
		'taxonomy_filter'			=> false,							// [ true | false ] - Set to true to filter by taxonomy
		'taxonomy_key'				=> 'commodity_category',			// The key of the taxonomy we wish to filter by
		'taxonomy_terms'			=> 'volunteer',						// The terms (uses slug), will accept a string or array
		'use_featured_image'		=> false 							// [ true | false ] - Set to true to only use posts with a featured image
	);

	$return_args					= array();
	$r 								= array_merge( $defaults, $args );
	$meta_query_args				= null;
	$tax_query_args 				= null;

	if( $r['use_featured_image'] )
	{
		$meta_query_args 			= 	array(
											'relation' 		=> 'AND',
											array(
												'key' 		=> '_thumbnail_id'
											)
										);
	}

	if( $r['featured'] )
	{
		if( is_array( $r['featured_post_meta_key'] ) )
		{
			foreach( $r['featured_post_meta_key'] as $featured_post_meta_key )
			{
				$query = array(
					'key' 		=> $featured_post_meta_key,
		 			'value' 	=> 'true',
		 			'compare' 	=> '='
				);

				array_push( $meta_query_args, $query );
			}
		}
		elseif( is_string( $r['featured_post_meta_key'] ) )
		{
			$query = array(
				'key' 		=> $r['featured_post_meta_key'],
	 			'value' 	=> 'true',
	 			'compare' 	=> '='
			);

			array_push( $meta_query_args, $query );
		}
	}

	if( $r['taxonomy_filter'] )
	{
		$tax_query_args = array(
			'taxonomy' 	=> $r['taxonomy_key'],
			'field' 	=> 'slug',
			'terms' 	=> $r['taxonomy_terms']
		);
	}

	if ( get_query_var('paged') ) 
	{ 
		$paged = get_query_var('paged'); 
	}
	else if ( get_query_var('page') ) 
	{ 
		$paged = get_query_var('page'); 
	}
	else 
	{ 
		$paged = 1; 
	}

	if( !$r['show_pagination'] )
	{
		$paged = 1; 
	}

	$return_args = 	array(
						'paged'						=> $paged,
						'post_type'					=> $r['post_type'],
						'orderby'					=> $r['orderby'],
						'order'						=> $r['order'],
						'posts_per_page'			=> $r['posts_per_page'],
						'posts_per_archive_page'	=> $r['posts_per_page'],
						'showposts'					=> $r['posts_per_page'],
						'meta_query' 				=> $meta_query_args,
						'tax_query' 				=> array( $tax_query_args )
					);

	return $return_args;
}
?>