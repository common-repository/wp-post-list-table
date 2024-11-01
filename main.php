<?php 
/**
 * Plugin Name: WP Post List Table
 * Plugin URI: https://wpbean.com/wp-post-list-table
 * Description: Display WordPress post listing in table.
 * Author: wpbean
 * Version: 1.0.3
 * Author URI: https://wpbean.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: wp_post_list_table
 * Domain Path:       /languages
 *
*/

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Define TextDomain
 */

if ( !defined( 'WPB_PLT_TEXTDOMAIN' ) ) {
	define( 'WPB_PLT_TEXTDOMAIN', 'wp_post_list_table' );
}

/**
 * Internationalization
 */

function wpb_plt_textdomain() {
	load_plugin_textdomain( WPB_PLT_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'wpb_plt_textdomain' );


/**
 * Requred files
 */

require_once dirname( __FILE__ ) . '/inc/class.settings-api.php';
require_once dirname( __FILE__ ) . '/inc/class.settings-fields.php';
require_once dirname( __FILE__ ) . '/inc/class.post-list-table.php';