<?php
/**
 * @package Commodity
 * @version 2.1.2
 */

/*
Plugin Name:  Commodity
Plugin URI:   http://makedo.in/products/
Description:  Custom post type and meta boxes for products, including product and global discount codes. Turn on or off as much as you need.
Author:       Make Do
Version:      2.1.2
Author URI:   http://makedo.in
Licence:      GPLv2 or later
License URI:  http://www.gnu.org/licenses/gpl-2.0.html

/////////  VERSION HISTORY

1.0.0	First development version
1.0.1	Added custom meta box
1.0.2	JavaScript calculations
1.0.3 	PHP calculations
2.0.0 	Added discounts meta box
2.0.1 	Added admin options
2.0.2 	Post thumbnail fix
2.0.3 	Added archive support
2.0.4 	Restructured project files
2.0.5 	Fixed windows compatibility issue
2.1.0	Hide meta boxes by default
2.1.1	Meta box bug fix

/////////  CURRENT FUNCTIONALITY

1  - Create custom post type
2  - Create commodity category custom taxonomy
3  - Admin scripts
4  - Create cost custom meta box
5  - Helper to set the decimal place
6  - Create discounts custom meta box
7  - Options screen
8  - Query arguments

*/

// 1  - Create custom post type
require_once 'admin-post-type-commodity-products.php';

// 2  - Create commodity category custom taxonomy
require_once 'admin-taxonomy-commodity-category.php';

// 3  - Admin scripts
require_once 'admin-scripts.php';

// 4  - Create cost custom meta box
require_once 'admin-meta-box-commodity-cost.php';

// 5  - Helper to set the decimal place
require_once 'helper-set-decimal-point.php';

// 6  - Create discounts custom meta box
require_once 'admin-meta-box-commodity-discounts.php';

// 7  - Options screen
require_once 'admin-options.php';

// 8  - Query arguments
require_once 'ui-query-arguments-commodity.php';
?>