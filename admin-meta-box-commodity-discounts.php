<?php
/**
 * @package Commodity
 */

/**
 * 
 * @since  		1.0.0
 * 
 * Custom meta box for discounts
 * 
 */
function commodity_discounts_meta_box() {

	// Only add the box to the selected post types
	$screens 		= array();
	$post_types 	= get_post_types( array('public' => true) );

	foreach( $post_types as $post_type)
	{
		if( get_option('_commodity_show_discounts_on_' . $post_type ) === 'show' )
		{
			array_push( $screens, $post_type );
		}
	}

	foreach ( $screens as $screen ) 
	{
		add_meta_box(
			'commodity_discounts',
			'Discount codes',
			'commodity_discounts_render_meta_box',
			$screen
		);
	}

}
add_action( 'add_meta_boxes', 'commodity_discounts_meta_box' );



/**
 * 
 * @since  		1.0.0
 * 
 * Render the disounts meta box
 * 
 */
function commodity_discounts_render_meta_box( $post ) {

	$key_array = array();

	foreach( array_keys( get_post_meta( $post->ID ) ) as $key )
	{
		if( strpos( $key, '_commodity_discount_code_' ) === 0 )
		{
			if( !in_array( str_replace( '_commodity_discount_code_', '', $key ), $key_array ) )
			{
				array_push( $key_array, str_replace( '_commodity_discount_code_', '', $key ) );
			}
		}
	}

	?>

		<div class="commodity_discounts cf">
			<p>Intro text goes here:</p>
			<table cellpadding="0" cellspacing="0" class="commodity_discounts_table">
				<thead>
					<tr>
						<th width="50%" scope="col"><label for="commodity_discount_name">Name</label></th>
						<th scope="col"><label for="commodity_discount_code">Code</label></th>
						<th scope="col"><label for="commodity_discount_type">Type</label></th>
						<th scope="col"><label for="commodity_discount_discount">Disount</label></th>
						<th scope="col"><label for="commodity_discount_expires">Expires</label></th>
						<th scope="col">Option</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td><input type="text" id="commodity_discount_name" name="commodity_discount_name"/> </td>
						<td><input type="text" id="commodity_discount_code" name="commodity_discount_code" maxlength="8"/></td>
						<td>
							<select id="commodity_discount_type" name="commodity_discount_type"/>>
								<option value="price">£</option>
								<option value="percent">%</option>
							</select>
						</td>
						<td><input type="text" id="commodity_discount_discount" name="commodity_discount_discount" value="0.00" /></td>
						<td><input type="date" id="commodity_discount_expires" name="commodity_discount_expires"/></td>
						<td><input type="submit" class="button button-small" id="commodity_discount_add" name="commodity_discount_add" value="Add"/></td>
					</tr>
				</tfoot>
				<tbody>
					<?php

						foreach( $key_array as $key )
						{
							$key_date = get_post_meta( $post->ID, '_commodity_discount_expires_' . $key ,true );
							if( empty($key_date) )
							{
								$key_date = 'N/A';
							}

							?>
								<tr>
									<td><?php echo get_post_meta( $post->ID, '_commodity_discount_name_' . $key ,true ); ?></td>
									<td><?php echo $key; ?></td>
									<td><?php echo ( get_post_meta( $post->ID, '_commodity_discount_type_' . $key ,true ) == 'price' ) ? '£' : '%'; ?></td>
									<td><?php echo get_post_meta( $post->ID, '_commodity_discount_discount_' . $key ,true ); ?></td>
									<td><?php echo $key_date; ?></td>
									<td><input type="submit" class="button button-small" id="commodity_discount_remove_<?php echo $key;?>" name="commodity_discount_remove_<?php echo $key;?>" value="Remove"/></td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>

	<?php

	wp_nonce_field( 'submit_commodity_discounts', 'commodity_discounts_nonce' ); 
}


/**
 * 
 * @since  		2.0.0
 * 
 * Handle the discount meta box post data
 * 
 */
function commodity_discount_handle_post_data( $post_id )
{
	$nonce_key							= 'commodity_discounts_nonce';
	$nonce_action						= 'submit_commodity_discounts';
	$key_array 							= array();

	// If it is just a revision don't worry about it
	if ( wp_is_post_revision( $post_id ) )
		return $post_id;

	// Check it's not an auto save routine
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;

	// Verify the nonce to defend against XSS
	if ( !isset( $_POST[$nonce_key] ) || !wp_verify_nonce( $_POST[$nonce_key], $nonce_action ) )
		return $post_id;

	// Check that the current user has permission to edit the post
	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;

	foreach( array_keys( get_post_meta( $post->ID ) ) as $key )
	{
		if( strpos( $key, '_commodity_discount_code_' ) === 0 )
		{
			if( !in_array( str_replace( '_commodity_discount_code_', '', $key ), $key_array ) )
			{
				array_push( $key_array, str_replace( '_commodity_discount_code_', '', $key ) );
			}
		}
	}

	$commodity_discount_name 		= $_POST['commodity_discount_name'];
	$commodity_discount_code 		= $_POST['commodity_discount_code'];
	$commodity_discount_type 		= $_POST['commodity_discount_type'];
	$commodity_discount_discount 	= $_POST['commodity_discount_discount'];
	$commodity_discount_expires 	= $_POST['commodity_discount_expires'];

	if( !empty( $commodity_discount_name ) && !empty( $commodity_discount_code ) && !empty( $commodity_discount_type ) && !empty( $commodity_discount_discount ) )
	{
		$key_value = $commodity_discount_code;
		$key_value = strtoupper( $key_value );
		$key_value = preg_replace( '/[^0-9A-Z-_]/', '', $key_value );
		
		if( strlen( $key_value ) > 8 )
		{
			$key_value = substr( $key_value, 0, 8 );
		}

		if( in_array( $key_value, $key_array) )
		{
			return $post_id;
		}

		$commodity_discount_name = preg_replace( '/[^0-9a-zA-Z\s]/', '', $commodity_discount_name );
		$commodity_discount_name = preg_replace( '/\s{2,}/', ' ', $commodity_discount_name );
		$commodity_discount_name = trim( $commodity_discount_name );

		if( strlen( $commodity_discount_name ) > 240 )
		{
			$commodity_discount_name = substr( $commodity_discount_name, 0, 240 );
		}

		if( $commodity_discount_type == 'percent' )
		{
			$commodity_discount_discount = commodity_set_decimal_place( $commodity_discount_discount, 2, true );
		}
		else if( $commodity_discount_type == 'price' )
		{
			$commodity_discount_discount = commodity_set_decimal_place( $commodity_discount_discount, 2, false );
		}
		else
		{
			return $post_id;
		}

		update_post_meta( $post_id, '_commodity_discount_name_' 		. $key_value , $commodity_discount_name );
		update_post_meta( $post_id, '_commodity_discount_code_' 		. $key_value , $key_value );
		update_post_meta( $post_id, '_commodity_discount_type_' 		. $key_value , $commodity_discount_type );
		update_post_meta( $post_id, '_commodity_discount_discount_' 	. $key_value , $commodity_discount_discount );
	
		if( !empty( $commodity_discount_expires ) )
		{
			update_post_meta( $post_id, '_commodity_discount_expires_' 	. $key_value , $commodity_discount_expires );
		}
	}

	foreach( $_POST as $key => $value ) 
	{
		if( strpos( $key, 'commodity_discount_remove_' ) === 0 ) 
		{
			$key_value = str_replace( 'commodity_discount_remove_', '', $key );

			delete_post_meta( $post_id, '_commodity_discount_name_' 		. $key_value );
			delete_post_meta( $post_id, '_commodity_discount_code_' 		. $key_value );
			delete_post_meta( $post_id, '_commodity_discount_type_' 		. $key_value );
			delete_post_meta( $post_id, '_commodity_discount_discount_' 	. $key_value );
			delete_post_meta( $post_id, '_commodity_discount_expires_' 		. $key_value );
		}
	}
}
add_action( 'save_post', 'commodity_discount_handle_post_data' );
?>