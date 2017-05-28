<?php

/*
Plugin Name: Include IncludeFontAwesome
Plugin URI: http://my-site.org/plugins/bootstrap-include/
Description: This is a plugin included IncludeFontAwesome to theme
Author: ZloyLeva
Version: 1.0
Author URI: http://localhost/
*/

class IncludeFontAwesome{

    function __construct()
    {
        add_action( 'init', array( $this, 'load_font_awesome_scripts' ) );
    }

    /**
     * Register scripts and styles
     */
    function load_font_awesome_scripts(){
        wp_enqueue_style( 'fontawesome-styles', plugin_dir_url( __FILE__ ) . 'css/font-awesome.css' );
    }

}
new IncludeFontAwesome();