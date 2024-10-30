var commodity_any_input 			= jQuery('.commodity_cost input');
var commodity_vat_global_rate		= commodity_object.vat_rate;
var	commodity_cost					= jQuery('#commodity_price');
var	commodity_discount				= jQuery('#commodity_discount');
var	commodity_discount_percent 		= jQuery('#commodity_discount_percent');
var commodity_discount_percent_old 	= 0;
var	commodity_quantity 				= jQuery('#commodity_quantity');
var	commodity_shipping 				= jQuery('#commodity_shipping');
var	commodity_shipping_total		= jQuery('#commodity_shipping_total');
var	commodity_shipping_vat_rate		= jQuery('#commodity_shipping_vat_rate');
var	commodity_total					= jQuery('#commodity_total');
var	commodity_vat_rate				= jQuery('#commodity_vat_rate');
var commodity_vat 					= jQuery('#commodity_vat');

var commodity_discount_any_input 	= jQuery('.commodity_discounts input');
var commodity_discount_name 		= jQuery('#commodity_discount_name');
var commodity_discount_code 		= jQuery('#commodity_discount_code');
var commodity_discount_type 		= jQuery('#commodity_discount_type');
var commodity_discount_discount 	= jQuery('#commodity_discount_discount');
var commodity_discount_expires 		= jQuery('#commodity_discount_expires');
var commodity_disount_add			= jQuery('#commodity_discount_add');

var commodity_options				= jQuery('.commodity_options input');
var	commodity_global_vat_rate		= jQuery('#commodity_global_vat_rate');

// Disabled the discount add button by default
commodity_disount_add.prop('disabled', true);


/**
 *
 * @since  1.0.2
 * 
 * Default non numeric characters and set decimal places
 * 
 * @param  object 	obj 		A jQuery object
 * @param  int 		dp 			The ammount of decimal places [ 0 | 2 ]
 * @param  bool 	percent 	Is this a percent [ true | false ]
 * 
 */
function commodity_set_decimal_place( obj, dp, percent )
{
	if( !jQuery.isNumeric( obj.val() ) || obj.val() < 0 )
	{
		if( dp == 2 )
		{
			obj.val('0.00');
		}
		else
		{
			obj.val(0);
		}
	}
	else if( dp == 2 && ( obj.val().toString().indexOf('.') === -1 || obj.val().toString().split('.')[1].length != 2 ) )
	{
		obj.val( parseFloat( obj.val() ).toFixed(2) );
	}
	else if( dp == 0 && obj.val().toString().indexOf('.') > -1 )
	{
		obj.val( parseFloat( obj.val() ).toFixed(0) );
	}

	// Make sure all values have a leading 0 if they are decimal places
	if( obj.val().toString().indexOf('.') > -1 && obj.val().toString().split('.')[0].length == 0 )
	{
		obj.val( '0' + obj.val().toString() );
	}

	// Dont let percentages go over 100%
	if( percent && obj.val() > 100)
	{
		obj.val(100);
	}
}

/**
 * 
 * When an input box is focused on, set old values for comparison
 * 
 */
commodity_any_input.bind('focus', function(){
	commodity_discount_percent_old = commodity_discount_percent.val();
});

/**
 * 
 * When input boxes have the focus shifted (blured) do calculations
 * 
 */
commodity_any_input.bind('blur', function(){

	// Set the decimal places correctly, and make sure we have no non numeric characters
	commodity_set_decimal_place( commodity_cost, 2, false );
	commodity_set_decimal_place( commodity_discount, 2, false );
	commodity_set_decimal_place( commodity_discount_percent, 2, true );
	commodity_set_decimal_place( commodity_quantity, 0, false );
	commodity_set_decimal_place( commodity_shipping, 2, false );
	commodity_set_decimal_place( commodity_shipping_total, 2, false );
	commodity_set_decimal_place( commodity_shipping_vat_rate, 2, true );
	commodity_set_decimal_place( commodity_total, 2, false );
	commodity_set_decimal_place( commodity_vat_rate, 2, true );
	commodity_set_decimal_place( commodity_vat, 2, false );

	// Calculate the discounts
	if( jQuery(this).attr('id') == commodity_cost.attr('id') )
	{
		if( commodity_cost.val() != 0 ) 
		{
			if( commodity_discount.val() != 0 )
			{
				commodity_discount_percent.val( commodity_discount.val() / commodity_cost.val() * 100 );
				commodity_set_decimal_place( commodity_discount_percent, 2, true );
			}
			else if( commodity_discount_percent.val() != 0 )
			{
				commodity_discount.val( commodity_cost.val() * commodity_discount_percent.val() / 100 );
				commodity_set_decimal_place( commodity_discount, 2, false );
			}
			else
			{
				commodity_discount.val('0.00');
				commodity_discount_percent.val('0.00');
			}
		}
		else
		{
			commodity_discount.val('0.00');
			commodity_discount_percent.val('0.00');
		}
	}
	else if( jQuery(this).attr('id') == commodity_discount.attr('id') )
	{
		if( commodity_cost.val() != 0 )
		{
			if( commodity_discount.val() > commodity_cost.val() )
			{
				commodity_discount.val('0.00');
			}
			commodity_discount_percent.val( commodity_discount.val() / commodity_cost.val() * 100 );
			commodity_set_decimal_place( commodity_discount_percent, 2, true );
		}
	}
	else if( jQuery(this).attr('id') == commodity_discount_percent.attr('id') )
	{
		if( commodity_cost.val() != 0 && commodity_discount_percent.val() != commodity_discount_percent_old )
		{
			commodity_discount.val( commodity_cost.val() * commodity_discount_percent.val() / 100 );
			commodity_set_decimal_place( commodity_discount, 2, false );
		}
	}

	// Calculate the VAT
	if( commodity_cost.val() != 0 )
	{
		if( commodity_vat_rate.val() != 0 )
		{
			commodity_vat.val( ( commodity_cost.val() - commodity_discount.val() ) * commodity_vat_rate.val() / 100 );
			commodity_set_decimal_place( commodity_vat, 2, false );
		}
		else
		{
			commodity_vat.val('0.00');
		}
	}
	else
	{
		commodity_vat.val('0.00');
	}

	// Calculate the shipping
	if( commodity_shipping.val() != 0 )
	{
		if( commodity_shipping_vat_rate.val() != 0 )
		{
			commodity_shipping_total.val( commodity_shipping.val() * commodity_shipping_vat_rate.val() / 100 );
			commodity_shipping_total.val( parseFloat( commodity_shipping_total.val() ) + parseFloat( commodity_shipping.val() ) );
			commodity_set_decimal_place( commodity_shipping_total, 2, false );
		}
		else
		{
			commodity_shipping_total.val( commodity_shipping.val() );
		}
	}

	// Calculate the total
	
	commodity_total.val(
		parseFloat( commodity_cost.val() ) -
		parseFloat( commodity_discount.val() ) +
		parseFloat( commodity_vat.val() ) +
		parseFloat( commodity_shipping_total.val() )
	);
	commodity_set_decimal_place( commodity_total, 2, false );


});



/**
 * 
 * When input boxes have the focus shifted (blured) do calculations
 * 
 */
commodity_discount_any_input.bind('blur', function(){

	if( commodity_discount_type.val() == 'percent' )
	{
		commodity_set_decimal_place( commodity_discount_discount, 2, true );
	}
	else
	{
		commodity_set_decimal_place( commodity_discount_discount, 2, false );
	}

	if( jQuery(this).attr('id') == commodity_discount_code.attr('id') )
	{
		commodity_discount_code.val( commodity_discount_code.val().toUpperCase().replace(/[^0-9A-Z-_]/g,'') );
		if( commodity_discount_code.val().length > 8 )
		{
			commodity_discount_code.val( commodity_discount_code.val().substr( 0, 8 ) );
		}
	}

	if( jQuery(this).attr('id') == commodity_discount_name.attr('id') )
	{
		commodity_discount_name.val( jQuery.trim( commodity_discount_name.val().replace(/[^0-9a-zA-Z\s]/g,'').replace(/\s{2,}/g, ' ') ) );
		if( commodity_discount_name.val().length > 240 )
		{
			commodity_discount_name.val( commodity_discount_name.val().substr( 0, 240 ) );
		}
	}

	if( commodity_discount_name.val() != null && commodity_discount_name.val() !== '' && 
		commodity_discount_code.val() != null && commodity_discount_code.val() !== '' &&
		commodity_discount_discount.val() > 0 )
	{
		commodity_disount_add.prop('disabled', false);
	}
	else
	{
		commodity_disount_add.prop('disabled', true);
	}
});


/**
 * 
 * When input boxes have the focus shifted (blured) do calculations
 * 
 */
commodity_options.bind('blur', function(){

	commodity_set_decimal_place( commodity_global_vat_rate, 2, true );

});