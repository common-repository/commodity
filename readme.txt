=== Commodity ===
Contributors: mwtsn
Donate link: 
Tags: products, pounds, uk, custom post type, basket, for sale, shopping, cart, vat, discount, discounts, sale
Requires at least: 3.3
Tested up to: 4.0
Stable tag: 2.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Custom post type and meta boxes for products, including product and global discount codes. Turn on or off as much as you need.

== Description ==

Created by [Make Do](http://makedo.in/), this plugin gives you a custom post type, and a range of meta boxes for the rendering products (including product and global discount codes). You can turn off as much as you like, and use the meta boxes on your own post types. Together you can use these to build your own shopping cart, price list, menu, discount system, etc.

= Commodity features =

* Renders a products custom post type (can be turned off)
* Renders a 'product value' cutom meta box, that can be added to any post type
* Renders a 'discount code' cutom meta box, that can be added to any post type
* Has a global discount code box in the options menu
* Handles VAT and other UK based product cost calculations

View the FAQ section for usage instructions.

If you are using this plugin in your project [we would love to hear about it](mailto:hello@makedo.in).

== Installation ==

1. Backup your WordPress install
2. Upload the plugin folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. The products custom post type, with the values and discounts meta boxes enabled
2. The options screen

== Frequently asked questions ==

= If I want to grab the products in my loop, what is the default custom post type name? =

It is: 'commodity_products'

= I've noticed that the products Custom Post Type has a category area, if I want to filter by this what is the taxonomy name? = 

It is also called: 'commodity_category'

= What are the names of all the meta information for the 'Product values' meta box? =

Those are:

* '_commodity_price'
* '_commodity_discount'
* '_commodity_discount_percent'
* '_commodity_quantity'
* '_commodity_shipping'
* '_commodity_shipping_total'
* '_commodity_shipping_vat_rate'
* '_commodity_total'
* '_commodity_vat_rate'
* '_commodity_vat'

= What are the names of all the meta information for the 'Discounts' meta box? =

So that they can be found quicker by queries, the discounts use the discount code that you set as part of the meta key. 

You can access them like so:

* '_commodity_discount_code_[discount code]'
* '_commodity_discount_name_[discount code]'
* '_commodity_discount_type_[discount code]'
* '_commodity_discount_discount_[discount code]'
* '_commodity_discount_expires_[discount code]'


= What are the names of all the meta information for the 'Global Discounts'? =

These work exactly the same as the 'Discounts' meta box, apart from you can get them using `get_option()`.

= What functions can I use? =

You can use:

* commodity_query_arguements()

The query accepts arguments.

= What does the commodity_query_arguements() function do? =

This function provides arguments for you to filter the products (or your own post types) creating a custom Loop. You can use it like so:

`get_posts( commodity_query_arguements( $args ) );`

It accepts the following arguments as an array (or you can leave the $args empty to use the defaults):

`
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
get_posts( commodity_query_arguements( $defaults ) );
`

= Can I contribute? =

Sure thing, the GitHub repository is right here: https://github.com/mwtsn/commodity

== Changelog ==

= 2.1.2	=
* Tested with WordPress 4.0

= 2.1.1	=
* Meta box bug fix

= 2.1.0 = 
* Hide meta boxes by default

= 2.0.5 =	
* Fixed Windows compatibility issue

= 2.0.4 =	
* Restructured project files

= 2.0.2 =
* Added archive support

= 2.0.2 =
* Featured image support fix

= 2.0.1 =
* Initial WordPress repository release

== Upgrade notice ==

There have been no breaking changes so far.