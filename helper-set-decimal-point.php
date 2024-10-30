<?php
/**
 * @package Commodity
 */

/**
 * 
 * @since  		1.0.3
 * 
 * Helper to calculate the correct ammount of decimal places
 *
 * @param  float 		$value        	The original value
 * @param  int 			$decial_place 	The ammount of decimal places
 * @param  bool 		$is_percent   	If the value is a percentage
 * 
 * @return float | int 					Returns the value formated as int or float
 * 
 */
function commodity_set_decimal_place( $value, $decimal_place, $is_percent )
{
	// Make sure the number is not numeric or negative
	if( !is_Numeric( $value ) || $value < 0 )
	{
		if( $decimal_place == 2 )
		{
			$value = '0.00';
		}
		else
		{
			$value = 0;
		}
	}
	
	// If there are two decimal points, but the number is whole, add the decimal points
	if( $decimal_place == 2 && strpos( $value, '.' ) === false )
	{
		$value = round( $value, 2, PHP_ROUND_HALF_EVEN );

		$value = number_format( $value, 2 );
	}

	// If there are two decimal points, and the value of the decmal is not 2 dp, round to 2 dp
	if( $decimal_place == 2 && strpos( $value, '.' ) !== false )
	{
		list( $whole, $decimal ) = explode( ".", $value );

		if( strlen( $decimal ) != 2 )
		{
			$value = round( $value, 2, PHP_ROUND_HALF_EVEN );
		}

		$value = number_format( $value, 2 );
	}

	// If the decimal place is 0, but there is a decimal point, round it to 0 dp
	if( $decimal_place == 0 && strpos( $value, '.' ) !== false )
	{
		$value = round( $value, 0, PHP_ROUND_HALF_EVEN );
	}

	// Make sure all values have a leading 0 if they are decimal places
	if( strpos( $value, '.' ) !== false )
	{
		list( $whole, $decimal ) = explode( ".", $value );

		if( strlen( $decimal ) == 0 )
		{
			$value = '0' . $value;
		}
	}

	// Dont let percentages go over 100%
	if( $is_percent && $value > 100)
	{
		$value = 100;
	}

	return $value;
}
?>