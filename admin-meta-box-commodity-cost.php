<?php
/**
 * @package Commodity
 */

/**
 * 
 * @since  		1.0.0
 * 
 * Custom meta box for price per product
 * 
 */
function commodity_cost_meta_box() {

	// Only add the box to the selected post types
	$screens 		= array();
	$post_types 	= get_post_types( array('public' => true) );

	foreach( $post_types as $post_type)
	{
		if( get_option('_commodity_show_product_values_on_' . $post_type ) === 'show' )
		{
			array_push( $screens, $post_type );
		}
	}

	foreach ( $screens as $screen ) 
	{
		add_meta_box(
			'commodity_cost',
			'Product values',
			'commodity_cost_render_meta_box',
			$screen,
			'side',
			'high'
		);
	}

}
add_action( 'add_meta_boxes', 'commodity_cost_meta_box' );


/**
 * 
 * @since  		1.0.0
 * 
 * Render the cost meta box
 * 
 */
function commodity_cost_render_meta_box( $post ) {

	$commodity_cost_value 				= get_post_meta( $post->ID, '_commodity_price', true );
	$commodity_discount_value 			= get_post_meta( $post->ID, '_commodity_discount', true );
	$commodity_discount_percent_value 	= get_post_meta( $post->ID, '_commodity_discount_percent', true );
	$commodity_quantity_value 			= get_post_meta( $post->ID, '_commodity_quantity', true );
	$commodity_shipping_value 			= get_post_meta( $post->ID, '_commodity_shipping', true );
	$commodity_shipping_total_value 	= get_post_meta( $post->ID, '_commodity_shipping_total', true );
	$commodity_shipping_vat_rate_value	= get_post_meta( $post->ID, '_commodity_shipping_vat_rate', true );
	$commodity_total_value 				= get_post_meta( $post->ID, '_commodity_total', true );
	$commodity_vat_rate 				= get_option('_commodity_global_vat_rate');
	$commodity_vat_rate_value 			= get_post_meta( $post->ID, '_commodity_vat_rate', true );
	$commodity_vat_value 				= get_post_meta( $post->ID, '_commodity_vat', true );

	?>

		<div class="commodity_cost cf">

			<p>
				<div class="row cf">
					<div class="label__container">
						<strong>
							<label class="label-inline" for="commodity_quantity">Units in stock</label>
						</strong>
					</div>
					<div class="input__container">
							<input size="8" type="text" id="commodity_quantity" name="commodity_quantity" value="<?php echo ( isset( $commodity_quantity_value ) && !empty( $commodity_quantity_value ) ) ? $commodity_quantity_value : '0';?>"/>
					</div>
				</div>
			</p>
		</div>

		<?php echo '</div><hr/><div class="inside">'; ?>

		<div class="commodity_cost cf">
			<p>
				<div class="row cf">
					<div class="label__container">
						<strong>
							<label class="label-inline" for="commodity_price">Price per unit</label>
						</strong>
					</div>
					<div class="input__container">
						<div class="input__wrapper">
							<span class="help-inline">£</span><input size="5" type="text" id="commodity_price" name="commodity_price" value="<?php echo ( isset( $commodity_cost_value ) && !empty( $commodity_cost_value ) ) ? $commodity_cost_value : '0.00';?>"/>
						</div>
					</div>
				</div>
			</p>
			<p>
				<div class="row cf">
					<div class="label__container">
						<strong>
							<label class="label-inline" for="commodity_discount">Sale discount</label>
						</strong>
					</div>
					<div class="input__container">
						<div class="input__wrapper">
							<span class="help-inline">£</span><input size="5" type="text" id="commodity_discount" name="commodity_discount" value="<?php echo ( isset( $commodity_discount_value ) && !empty( $commodity_discount_value ) ) ? $commodity_discount_value : '0.00';?>"/>
						</div>
					</div>
				</div>
				<br/>
				<div class="row cf">
					<div class="label__container">
						<em><label class="label-inline muted" for="commodity_discount_percent">% of price</label></em>
					</div>
					<div class="input__container">
						<div class="input__wrapper right">
							<input size="5" type="text" id="commodity_discount_percent" name="commodity_discount_percent" value="<?php echo ( isset( $commodity_discount_percent_value ) && !empty( $commodity_discount_percent_value ) ) ? $commodity_discount_percent_value : '0.00';?>"/><span class="help-inline right">%</span>
						</div>
					</div>
				</div>
			</p>
		</div>
		
		<?php echo '</div><hr/><div class="inside">'; ?>

		<div class="commodity_cost cf">
			<p>
				<div class="row cf">
					<div class="label__container">
						<strong>
							<label class="label-inline" for="commodity_vat_rate">VAT rate</label>
						</strong>
					</div>
					<div class="input__container">
						<div class="input__wrapper right">
							<input size="5" type="text" id="commodity_vat_rate" name="commodity_vat_rate" value="<?php echo ( isset( $commodity_vat_rate_value ) && !empty( $commodity_vat_rate_value ) ) ? $commodity_vat_rate_value : $commodity_vat_rate;?>"/><span class="help-inline right">%</span>
						</div>
					</div>
				</div>
			</p>
			<p>
				<div class="row cf">
					<div class="label__container">
						<strong>
							<label class="label-inline" for="commodity_vat">VAT</label>
						</strong>
					</div>
					<div class="input__container">
						<div class="input__wrapper">
							<span class="help-inline">£</span><input size="5" type="text" id="commodity_vat" name="commodity_vat" disabled="disabled" class="disabled" value="<?php echo ( isset( $commodity_vat_value ) && !empty( $commodity_vat_value ) ) ? $commodity_vat_value : '0.00';?>"/>
						</div>
					</div>
				</div>
			</p>

		</div>
		
		<?php echo '</div><hr/><div class="inside">'; ?>

		<div class="commodity_cost cf">
			<p>
				<div class="row cf">
					<div class="label__container">
						<strong>
							<label class="label-inline" for="commodity_shipping">Shipping</label>
						</strong>
					</div>
					<div class="input__container">
						<div class="input__wrapper">
							<span class="help-inline">£</span><input size="5" type="text" id="commodity_shipping" name="commodity_shipping" value="<?php echo ( isset( $commodity_shipping_value ) && !empty( $commodity_shipping_value ) ) ? $commodity_shipping_value : '0.00';?>"/> 
						</div>
					</div>
				</div>
				<br/>
				<div class="row cf">
					<div class="label__container">
						<em>
							<label class="screen-reader-text" for="commodity_shipping_vat_rate">Shipping VAT rate</label> 
							<span class="label-inline muted" title="Shipping VAT is charged at the standard rate by default">+ VAT @ <input size="8" id="commodity_shipping_vat_rate" name="commodity_shipping_vat_rate" value="<?php echo ( isset( $commodity_shipping_vat_rate_value ) && !empty( $commodity_shipping_vat_rate_value ) ) ? $commodity_shipping_vat_rate_value : $commodity_vat_rate;?>"/> %</span>
						</em>
					</div>
					<div class="input__container">
						<div class="input__wrapper">
							<label class="screen-reader-text" for="commodity_shipping_total">Shipping Total</label> 
							<span class="help-inline">£</span><input size="5" type="text" id="commodity_shipping_total" name="commodity_shipping_total" disabled="disabled" class="disabled" value="<?php echo ( isset( $commodity_shipping_total_value ) && !empty( $commodity_shipping_total_value ) ) ? $commodity_shipping_total_value : '0.00';?>"/> 
						</div>
					</div>
				</div>
			</p>
		</div>

		<?php echo '</div><hr/><div class="inside">'; ?>

		<div class="commodity_cost cf">
			<p>
				<div class="row cf">
					<div class="label__container">
							<strong>
								<label class="label-inline" for="commodity_total">Total</label>
							</strong>
						</div>
				
					<div class="input__container">
						<div class="input__wrapper">
							<span class="help-inline">£</span><input size="5" type="text" id="commodity_total" name="commodity_total" disabled="disabled" class="disabled" value="<?php echo ( isset( $commodity_total_value ) && !empty( $commodity_total_value ) ) ? $commodity_total_value : '0.00';?>"/>
						</div>
					</div>
				</div>
			</p>

		</div>

	<?php

	wp_nonce_field( 'submit_commodity_cost', 'commodity_cost_nonce' ); 
}


/**
 * 
 * @since  		1.0.0
 * 
 * Handle the  meta box post data
 * 
 */
function commodity_cost_handle_post_data( $post_id )
{
	$nonce_key							= 'commodity_cost_nonce';
	$nonce_action						= 'submit_commodity_cost';

	$commodity_cost_key 				= '_commodity_price';
	$commodity_discount_key 			= '_commodity_discount';
	$commodity_discount_percent_key 	= '_commodity_discount_percent';
	$commodity_quantity_key 			= '_commodity_quantity';
	$commodity_shipping_key 			= '_commodity_shipping';
	$commodity_shipping_total_key 		= '_commodity_shipping_total';
	$commodity_shipping_vat_rate_key	= '_commodity_shipping_vat_rate';
	$commodity_total_key 				= '_commodity_total';
	$commodity_vat_rate_key 			= '_commodity_vat_rate';
	$commodity_vat_key 					= '_commodity_vat';

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

	$commodity_cost_value 				= ( isset( $_POST['commodity_price'] ) 				&& is_numeric( $_POST['commodity_price'] ) ) 				? $_POST['commodity_price'] 			: '0.00';
	$commodity_discount_value 			= ( isset( $_POST['commodity_discount'] ) 			&& is_numeric( $_POST['commodity_discount'] ) ) 			? $_POST['commodity_discount'] 			: '0.00';
	$commodity_discount_percent_value 	= ( isset( $_POST['commodity_discount_percent'] ) 	&& is_numeric( $_POST['commodity_discount_percent'] ) ) 	? $_POST['commodity_discount_percent'] 	: '0.00';
	$commodity_quantity_value 			= ( isset( $_POST['commodity_quantity'] ) 			&& is_numeric( $_POST['commodity_price'] ) ) 				? $_POST['commodity_price'] 			: '0';
	$commodity_shipping_value 			= ( isset( $_POST['commodity_shipping'] ) 			&& is_numeric( $_POST['commodity_shipping'] ) ) 			? $_POST['commodity_shipping'] 			: '0.00';
	$commodity_shipping_total_value 	= ( isset( $_POST['commodity_shipping_total'] ) 	&& is_numeric( $_POST['commodity_shipping_total'] ) ) 		? $_POST['commodity_shipping_total'] 	: '0.00';
	$commodity_shipping_vat_rate_value	= ( isset( $_POST['commodity_shipping_vat_rate'] ) 	&& is_numeric( $_POST['commodity_shipping_vat_rate'] ) ) 	? $_POST['commodity_shipping_vat_rate'] : '0.00';
	$commodity_total_value 				= ( isset( $_POST['commodity_total'] ) 				&& is_numeric( $_POST['commodity_total'] ) ) 				? $_POST['commodity_total'] 			: '0.00';
	$commodity_vat_rate_value 			= ( isset( $_POST['commodity_vat_rate'] ) 			&& is_numeric( $_POST['commodity_vat_rate'] ) ) 			? $_POST['commodity_vat_rate'] 			: '0.00';
	$commodity_vat_value 				= ( isset( $_POST['commodity_vat'] ) 				&& is_numeric( $_POST['commodity_vat'] ) ) 					? $_POST['commodity_vat'] : 			'0.00';

	$commodity_cost_value 				= commodity_set_decimal_place( $commodity_cost_value, 				2, false );
	$commodity_discount_value 			= commodity_set_decimal_place( $commodity_discount_value, 			2, false );
	$commodity_discount_percent_value 	= commodity_set_decimal_place( $commodity_discount_percent_value, 	2, true );
	$commodity_quantity_value 			= commodity_set_decimal_place( $commodity_quantity_value, 			0, false );
	$commodity_shipping_value 			= commodity_set_decimal_place( $commodity_shipping_value, 			2, false );
	$commodity_shipping_total_value 	= commodity_set_decimal_place( $commodity_shipping_total_value, 	2, false );
	$commodity_shipping_vat_rate_value	= commodity_set_decimal_place( $commodity_shipping_vat_rate_value, 	2, true );
	$commodity_total_value 				= commodity_set_decimal_place( $commodity_total_value, 				2, false );
	$commodity_vat_rate_value 			= commodity_set_decimal_place( $commodity_vat_rate_value, 			2, true );
	$commodity_vat_value 				= commodity_set_decimal_place( $commodity_vat_value, 				2, false );

	// Calculate the discounts
	if( $commodity_cost_value != 0 )
	{
		$commodity_discount_percent_value = ( $commodity_discount_value / $commodity_cost_value * 100 );
		$commodity_discount_percent_value = commodity_set_decimal_place( $commodity_discount_percent_value, 2, true );
	}
	else if( $commodity_discount_percent_value != 0) 
	{
		$commodity_discount_value = ( $commodity_cost_value * $commodity_discount_percent_value / 100 );
		$commodity_discount_value = commodity_set_decimal_place( $commodity_discount_value, 2, false );
	}
	else
	{
		$commodity_discount_value = '0.00';
		$commodity_discount_percent_value = commodity_set_decimal_place( $commodity_discount_percent_value, 2, false );
	}

	if( $commodity_discount_value > $commodity_cost_value )
	{
		$commodity_discount_value = $commodity_cost_value;
	}

	// Calculate the VAT
	if( $commodity_cost_value != 0  )
	{
		if( $commodity_vat_rate_value != 0 )
		{
			$commodity_vat_value = ( ( $commodity_cost_value -$commodity_discount_value ) * $commodity_vat_rate_value / 100 );
			$commodity_vat_value = commodity_set_decimal_place( $commodity_vat_value, 2, false );
		}
		else
		{
			$commodity_cost_value = '0.00';
		}
	}
	else
	{
		$commodity_cost_value = '0.00';
	}

	// Calculate the shipping
	if( $commodity_shipping_value != 0 )
	{
		if( $commodity_vat_rate_value != 0 )
		{
			$commodity_shipping_total_value = ( $commodity_shipping_value  * $commodity_vat_rate_value / 100 );
			$commodity_shipping_total_value = ( $commodity_shipping_total_value + $commodity_shipping_value );
			$commodity_shipping_total_value = commodity_set_decimal_place( $commodity_shipping_total_value, 2, false );
		}
		else
		{
			$commodity_shipping_total_value = $commodity_shipping_value;
		}
	}

	// Calculate the total
	$commodity_total_value = $commodity_cost_value - $commodity_discount_value + $commodity_vat_value + $commodity_shipping_total_value;
	$commodity_total_value = commodity_set_decimal_place( $commodity_total_value, 2, false );

	// Do the updates
	update_post_meta( $post_id, $commodity_cost_key, 					$commodity_cost_value );
	update_post_meta( $post_id, $commodity_discount_key, 				$commodity_discount_value );
	update_post_meta( $post_id, $commodity_discount_percent_key, 		$commodity_discount_percent_value );
	update_post_meta( $post_id, $commodity_quantity_key, 				$commodity_quantity_value );
	update_post_meta( $post_id, $commodity_shipping_key, 				$commodity_shipping_value );
	update_post_meta( $post_id, $commodity_shipping_total_key, 			$commodity_shipping_total_value );
	update_post_meta( $post_id, $commodity_shipping_vat_rate_key, 		$commodity_shipping_vat_rate_value );
	update_post_meta( $post_id, $commodity_total_key, 					$commodity_total_value );
	update_post_meta( $post_id, $commodity_vat_rate_key, 				$commodity_vat_rate_value );
	update_post_meta( $post_id, $commodity_vat_key, 					$commodity_vat_value );
}
add_action( 'save_post', 'commodity_cost_handle_post_data' );
?>