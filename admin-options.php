<?php
/**
 * @package Commodity
 */

/**
 * 
 * @since  		2.0.1
 * 
 * Register the options page
 * 
 */
function commodity_add_options_page() {

	add_options_page( 'Commodity', 'Commodity', 'manage_options', 'commodity-settings', 'commodity_render_options_page' );

	add_action( 'admin_init', 'commodity_register_settings' );
}
add_action('admin_menu', 'commodity_add_options_page');




/**
 * 
 * @since  		2.0.1
 * 
 * Register the options settings
 * 
 */
function commodity_register_settings() {

	$post_types = get_post_types( array('public' => true) );

	register_setting( 'commodity_group', '_commodity_global_vat_rate' );
	register_setting( 'commodity_group', '_commodity_global_vat_update' );
	register_setting( 'commodity_group', '_commodity_show_products_cpt' );

	foreach( $post_types as $post_type)
	{
		register_setting( 'commodity_group', '_commodity_show_product_values_on_' . $post_type );
	}

	foreach( $post_types as $post_type)
	{
		register_setting( 'commodity_group', '_commodity_show_discounts_on_' . $post_type );
	}

}

/**
 * 
 * @since  		2.0.1
 * 
 * Render the options page
 * 
 */
function commodity_render_options_page()
{	
	$global_vat_rate 	= '20.00';
	$post_types 		= get_post_types( array('public' => true) );
	sort( $post_types );
	$screens 		= array();

	foreach( $post_types as $post_type)
	{
		if($post_type == 'commodity_products')
		{
			if( get_option('_commodity_show_product_values_on_' . $post_type ) === false )
			{
				add_option( '_commodity_show_product_values_on_' . $post_type, 'show' );
			}

			if( get_option('_commodity_show_discounts_on_' . $post_type ) === false )
			{
				add_option( '_commodity_show_discounts_on_' . $post_type, 'show' );
			}
		}

		if( get_option('_commodity_show_product_values_on_' . $post_type ) === 'show' )
		{
			array_push( $screens, $post_type );
		}
	}

	if( get_option('_commodity_show_products_cpt') === false)
	{
		add_option( '_commodity_show_products_cpt', 'show' );
	}

	if( get_option('_commodity_global_vat_rate') === false )
	{
		add_option( '_commodity_global_vat_rate', $global_vat_rate );
		add_option( '_commodity_global_vat_rate_old', $global_vat_rate );
	}
	else
	{
		$vat_rate = get_option('_commodity_global_vat_rate');

		if( !is_numeric( $vat_rate ) || $vat_rate > 100 )
		{
			update_option( '_commodity_global_vat_rate', '100.00' );
		}
		else if( $vat_rate < 0 )
		{
			update_option( '_commodity_global_vat_rate', '0.00' );
		}
	}

	if( get_option('_commodity_global_vat_rate') != get_option('_commodity_global_vat_rate_old'))
	{
		$meta_posts 	= array();
		$vat_rate 		= get_option('_commodity_global_vat_rate');
		$update_vat 	= get_option('_commodity_global_vat_update');

		if( $update_vat == 'update' )
		{

			$meta_post_args = 	array(
									'post_type'		=> $screens,
									'meta_query' 	=> array(
										array(
											'key' 		=> '_commodity_vat_rate',
											'value' 	=> get_option('_commodity_global_vat_rate_old'),
											'compare' 	=> '='
										)
									)
								);

			$meta_posts = get_posts( $meta_post_args );

			foreach( $meta_posts as $meta )
			{
				update_post_meta( $meta->ID, '_commodity_vat_rate', $vat_rate );
			}

			$meta_post_args = 	array(
									'post_type'		=> $screens,
									'meta_query' 	=> array(
										array(
											'key' 		=> '_commodity_shipping_vat_rate',
											'value' 	=> get_option('_commodity_global_vat_rate_old'),
											'compare' 	=> '='
										)
									)
								);

			$meta_posts = get_posts( $meta_post_args );

			foreach( $meta_posts as $meta )
			{
				update_post_meta( $meta->ID, '_commodity_shipping_vat_rate', $vat_rate );
			}

		}

		delete_option( '_commodity_global_vat_update' );
		update_option( '_commodity_global_vat_rate_old', get_option('_commodity_global_vat_rate') );
	}

	?>
		<div class="wrap commodity_options">
			<h2>Commodity</h2>
			<form method="post" action="options.php">
			<?php 
				settings_fields( 'commodity_group' );
				do_settings_sections( 'commodity_group' );
			?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="commodity_global_vat_rate">Global VAT rate</label></th>
					<td>
						<input type="text" id="commodity_global_vat_rate" name="_commodity_global_vat_rate" value="<?php echo get_option('_commodity_global_vat_rate'); ?>" />
						<label class="screen-reader-text" for="commodity_global_vat_rate_old">Global VAT old rate</label>
						<input type="hidden" id="commodity_global_vat_rate_old" name="_commodity_global_vat_rate_old" value="<?php echo get_option('_commodity_global_vat_rate_old'); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="commodity_global_vat_update">Update the VAT value</label></th>
					<td><input type="checkbox" value="update" id="commodity_global_vat_update" name="_commodity_global_vat_update"><p class="description">This option will change all of the Commodity VAT values that are set to '<?php echo get_option('_commodity_global_vat_rate_old'); ?>',' to the new value.</p></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="commodity_show_products_cpt">Show the 'Products' post type</label></th>
					<td><input type="checkbox" value="show" id="commodity_show_products_cpt" name="_commodity_show_products_cpt"<?php echo ( get_option('_commodity_show_products_cpt') == 'show' ) ? ' checked' : '';?>></td>
				</tr>
				<tr valign="top">
					<th scope="row">Show 'Product values' custom meta on post type</th>
					<td>
						<?php
							foreach( $post_types as $post_type)
							{
								?>
									<span class="inline">
										<input type="checkbox" value="show" id="commodity_show_product_values_on_<?php echo $post_type;?>" name="_commodity_show_product_values_on_<?php echo $post_type;?>"<?php echo ( get_option('_commodity_show_product_values_on_' . $post_type ) == 'show' ) ? ' checked' : '';?>>
										<label for="commodity_show_product_values_on_<?php echo $post_type;?>">
										<?php
											
											$obj = get_post_type_object( $post_type );
											echo $obj->labels->singular_name;
										?>
										</label>
									</span>
								<?php
							}
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Show 'Discounts' custom meta on post type</th>
					<td>
						<?php
							foreach( $post_types as $post_type)
							{
								?>
									<span class="inline">
										<input type="checkbox" value="show" id="commodity_show_discounts_on_<?php echo $post_type;?>" name="_commodity_show_discounts_on_<?php echo $post_type;?>"<?php echo ( get_option('_commodity_show_discounts_on_' . $post_type ) == 'show' ) ? ' checked' : '';?>>
										<label for="commodity_show_discounts_on_<?php echo $post_type;?>">
										<?php
											
											$obj = get_post_type_object( $post_type );
											echo $obj->labels->singular_name;
										?>
										</label>
									</span>
								<?php
							}
						?>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
			</form>
			<form method="post">
			<?php

				$commodity_discount_name 		= isset( $_POST['commodity_discount_name'] ) ? $_POST['commodity_discount_name'] : '';
				$commodity_discount_code 		= isset( $_POST['commodity_discount_code'] ) ? $_POST['commodity_discount_code'] : '';
				$commodity_discount_type 		= isset( $_POST['commodity_discount_type'] ) ? $_POST['commodity_discount_type'] : '';
				$commodity_discount_discount 	= isset( $_POST['commodity_discount_discount'] ) ? $_POST['commodity_discount_discount'] : '';
				$commodity_discount_expires 	= isset( $_POST['commodity_discount_expires'] ) ? $_POST['commodity_discount_expires'] : '';
				
				$key_array = array();

				foreach( array_keys( wp_load_alloptions() ) as $key )
				{
					if( strpos( $key, '_commodity_discount_code_' ) === 0 )
					{
						if( !in_array( str_replace( '_commodity_discount_code_', '', $key ), $key_array ) )
						{
							array_push( $key_array, str_replace( '_commodity_discount_code_', '', $key ) );
						}
					}
				}

				if( !empty( $commodity_discount_name ) && !empty( $commodity_discount_code ) && !empty( $commodity_discount_type ) && !empty( $commodity_discount_discount ) )
				{
					$key_value = $commodity_discount_code;
					$key_value = strtoupper( $key_value );
					$key_value = preg_replace( '/[^0-9A-Z-_]/', '', $key_value );
					
					if( strlen( $key_value ) > 8 )
					{
						$key_value = substr( $key_value, 0, 8 );
					}

					if( !in_array( $key_value, $key_array) )
					{

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

						update_option( '_commodity_discount_name_' 		. $key_value , $commodity_discount_name );
						update_option( '_commodity_discount_code_' 		. $key_value , $key_value );
						update_option( '_commodity_discount_type_' 		. $key_value , $commodity_discount_type );
						update_option( '_commodity_discount_discount_' 	. $key_value , $commodity_discount_discount );
					
						if( !empty( $commodity_discount_expires ) )
						{
							update_option( $post_id, '_commodity_discount_expires_' 	. $key_value , $commodity_discount_expires );
						}
					}
				}
				foreach( $_POST as $key => $value ) 
				{
					if( strpos( $key, 'commodity_discount_remove_' ) === 0 ) 
					{
						$key_value = str_replace( 'commodity_discount_remove_', '', $key );

						delete_option( '_commodity_discount_name_' 		. $key_value );
						delete_option( '_commodity_discount_code_' 		. $key_value );
						delete_option( '_commodity_discount_type_' 		. $key_value );
						delete_option( '_commodity_discount_discount_' 	. $key_value );
						delete_option( '_commodity_discount_expires_' 	. $key_value );
					}
				}
				$key_array = array();

				foreach( array_keys( wp_load_alloptions() ) as $key )
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

			<h3>Global discount codes</h3>
			<table cellpadding="0" cellspacing="0" class="commodity_discounts_table commodity_discounts">
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
							$key_date = get_option( '_commodity_discount_expires_' . $key );
							if( empty($key_date) )
							{
								$key_date = 'N/A';
							}

							?>
								<tr>
									<td><?php echo get_option( '_commodity_discount_name_' . $key ); ?></td>
									<td><?php echo $key; ?></td>
									<td><?php echo ( get_option( '_commodity_discount_type_' . $key ) == 'price' ) ? '£' : '%'; ?></td>
									<td><?php echo get_option( '_commodity_discount_discount_' . $key ); ?></td>
									<td><?php echo $key_date; ?></td>
									<td><input type="submit" class="button button-small" id="commodity_discount_remove_<?php echo $key;?>" name="commodity_discount_remove_<?php echo $key;?>" value="Remove"/></td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
			</form>
		</div>
	<?php
}

/**
 * Add "Settings" action on installed plugin list
 */
function commodity_add_plugin_actions( $links ) {
	array_unshift( $links, '<a href="options-general.php?page=commodity-settings">Settings</a>');
	return $links;
}
add_action( 'plugin_action_links_commodity/index.php', 'commodity_add_plugin_actions' );

/**
 * Add links on installed plugin list
 */
function commodity_add_plugin_links( $links, $file ) 
{
	if( $file == 'commodity/index.php' ) {
		$rate_url = 'http://wordpress.org/support/view/plugin-reviews/commodity?rate=5#postform';
		$links[] = '<a href="' . $rate_url . '" target="_blank" title="Rate and Review this Plugin on WordPress.org">Rate this plugin</a>';
	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'commodity_add_plugin_links' , 10, 2);
?>