<?php
/*
Plugin Name: Hide price for not logined users
Plugin URI: http://wordpress.org/plugins/my-plugin/
Description: This is a plugin for hide price if user not login
Author: ZloyLeva
Version: 0.2.0
Author URI: http://localhost/
*/

class HidePrice{

	function __construct(){
		add_action('init', array( $this, 'my_theme_hide_price_not_authorized') );
	}

	function my_theme_hide_price_not_authorized() {
	  if ( !is_user_logged_in() ) {
	    // Hide price
	    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

	    add_filter( 'woocommerce_variation_is_active', 'my_theme_disable_variation', 10, 2 );
	    // hide button "add to cart"
	    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
	    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	    // Hide attribute
	    add_filter( 'woocommerce_attribute', 'my_theme_hide_attribute');
	  }
	}

	function my_theme_disable_variation() {
	    return false;
	}

	function my_theme_hide_attribute() {
	    return '';
	}

}
new HidePrice();