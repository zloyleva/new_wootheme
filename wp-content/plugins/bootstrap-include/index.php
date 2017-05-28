<?php

/*
Plugin Name: Include Bootstrap
Plugin URI: http://my-site.org/plugins/bootstrap-include/
Description: This is a plugin included Bootstrap to theme
Author: ZloyLeva
Version: 1.0
Author URI: http://localhost/
*/

class IncludeBootstrap{

    function __construct()
    {
        add_action( 'wp_enqueue_scripts', array( $this, 'load_bootstrap_scripts' ) );
    }

    /**
     * Register scripts and styles
     */
    function load_bootstrap_scripts(){
        wp_enqueue_style( 'bootstrap-styles', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css' );
        wp_enqueue_script('jquery');
        wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.js', array('jquery') );
    }

}
new IncludeBootstrap();