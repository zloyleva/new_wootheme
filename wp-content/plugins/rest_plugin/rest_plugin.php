<?php

/*
Plugin Name: REST Plugin Custom
Plugin URI: http://my-site.org/plugins/bootstrap-include/
Description: This is a plugin for REST things
Author: ZloyLeva
Version: 1.0
Author URI: http://localhost/
*/

// add_action( 'rest_api_init', function () {
//   register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
//     'methods' => 'GET',
//     'callback' => 'my_awesome_func',
//     // 'permission_callback' => function () {
//     //   return current_user_can( 'edit_others_posts' );
//     // },
//   ) );
// } );

// function my_awesome_func(WP_REST_Request $request){
// 	// print_r($request);
// 	// die();


// 	return array(
// 		'status'=>'done', 
// 		'rest_url'=>$request->get_url_params(),
// 		'rest_query'=>$request->get_query_params(),
// 		'rest_body_params'=> $request->get_body_params(),
//   		'json_params'=> $request->get_json_params(),
//   		'default_params'=> $request->get_default_params(),
//   		'get_params'=>$request->get_params(),
//   		'user'=>wp_get_current_user(),
// 	);
// }