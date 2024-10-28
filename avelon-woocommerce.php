<?php
 
/*
 
Plugin Name: Avelon Network
 
Plugin URI: https://avelonetwork.com/
 
Description: Plugin to enable Woocommerce stores to use Avelon Network.
 
Version: 1.0.3
 
Author: pixable
 
Author URI: https://pixable.co/
 
License: GPLv3
 
Text Domain: avelon
 
*/

// Test to see if WooCommerce is active.

$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';

if (
    !in_array( $plugin_path, wp_get_active_and_valid_plugins() )

) {

	function aveleon_notice(){
		echo '<div class="notice notice-warning is-dismissible">
			<p>Woocommerce needs to be installed in order for the Avelon Woocommerce Integration plugin to work properly</p>
		</div>';

	}
	add_action('admin_notices', 'aveleon_notice');

}

include( plugin_dir_path( __FILE__ ) . 'admin/settings.php' );

include( plugin_dir_path( __FILE__ ) . 'includes/header.php' );

include( plugin_dir_path( __FILE__ ) . 'includes/payload.php' );

include( plugin_dir_path( __FILE__ ) . 'woocommerce/checkout.php' );
include( plugin_dir_path( __FILE__ ) . 'woocommerce/products-endpoint.php' );

add_action('wp_enqueue_scripts', 'avelon_scripts');

function avelon_scripts() {
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_register_style( 'avelonstyle', $plugin_url . 'assets/style.css' );
	wp_enqueue_style( 'avelonstyle' );
}

