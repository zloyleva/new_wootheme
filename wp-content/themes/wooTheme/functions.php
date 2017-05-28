<?php

class WooTheme
{

    function __construct()
    {
        add_action('after_setup_theme', array($this, 'start_setup'));
        add_action('wp_enqueue_scripts', array($this, 'load_woo_theme_scripts'));
        add_filter('woocommerce_currency_symbol', array($this, 'add_ua_currency_symbol'), 10, 2);
        add_filter( 'woocommerce_checkout_fields' , array($this, 'custom_override_checkout_fields' ));

        add_action('wp_ajax_get_data_server', array($this, 'get_data_server_callback'));
        add_action('wp_ajax_nopriv_get_data_server', array($this, 'get_data_server_callback'));

        add_action('init', array($this, 'init_custom_post_type'));

        add_action( 'rest_api_init', function () {
          register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'my_awesome_func'),
          ) );
        } );
    }

    

    function my_awesome_func(WP_REST_Request $request){
        // print_r($request);
        // die();


        return array(
            'status'=>'done', 
            'rest_url'=>$request->get_url_params(),
            'rest_query'=>$request->get_query_params(),
            'rest_body_params'=> $request->get_body_params(),
            'json_params'=> $request->get_json_params(),
            'default_params'=> $request->get_default_params(),
            'get_params'=>$request->get_params(),
            'user'=>wp_get_current_user(),
        );
    }

    function start_setup() {
        add_theme_support( 'post-thumbnails' );
        register_nav_menus( array(
            'primary' => __( 'Primary Menu' ),
            'footer'  => __( 'Footer Links Menu' ),
            'top_phone' => __('Top phone menu'),
            'top_account' => __('Top account menu'),
        ) );
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
        add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat' ) );
        add_theme_support( 'custom-logo', array(
            'height'      => 200,
            'width'       => 200,
            'flex-height' => true,
        ) );
    }

    function load_woo_theme_scripts() {
        // Load our main stylesheet.
        wp_enqueue_style( 'my-css', get_stylesheet_uri() );
        wp_enqueue_style( 'wp-woo-styles', get_template_directory_uri() . '/css/style.css' );
        // wp_enqueue_style( 'fa-font-styles', get_template_directory_uri() . '/css/font-awesome.min.css' );
        wp_enqueue_script( 'jquery', true );
        wp_localize_script('fa-font-styles', 'get_data',
            array(
                'url' => admin_url('admin-ajax.php')
            )
        );
        wp_register_script( 'woo_theme_script',  get_template_directory_uri() . '/js/wootheme-script.js', array('jquery'), '1.0.1', true );
        wp_enqueue_script( 'woo_theme_script' );
    }

    function add_ua_currency_symbol( $currency_symbol, $currency ) {
        switch( $currency ) {
            case 'UAH': $currency_symbol = ' грн'; break;
        }
        return $currency_symbol;
    }

	/**
	 * Unset
	 * @param $fields
	 *
	 * @return mixed
	 */
    function custom_override_checkout_fields( $fields ) {
        unset($fields['billing']['billing_last_name']);
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_state']);

        unset($fields['shipping']['shipping_last_name']);
        unset($fields['shipping']['shipping_company']);
        unset($fields['shipping']['shipping_postcode']);
        unset($fields['shipping']['shipping_state']);
        return $fields;
    }


    function init_custom_post_type(){
        register_post_type('sale', array(
                'labels'             => array(
                    'name'               => 'Акции', // Основное название типа записи
                    'singular_name'      => 'Акция', // отдельное название записи типа Book
                    'add_new'            => 'Добавить акцию',
                    'add_new_item'       => 'Добавить новую акцию',
                    'edit_item'          => 'Редактировать акцию',
                    'new_item'           => 'Новая акция',
                    'view_item'          => 'Посмотреть акцию',
                    'search_items'       => 'Найти акцию',
                    'not_found'          =>  'Не найдено акций',
                    'not_found_in_trash' => 'В корзине акций не найдено',
                    'parent_item_colon'  => '',
                    'menu_name'          => 'Акции'

                  ),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'menu_icon'          => 'dashicons-products',
                'query_var'          => true,
                'rewrite'            => true,
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array('title','editor','author','thumbnail','excerpt','comments')
            ) 
        );

        register_post_type('news', array(
                'labels'             => array(
                    'name'               => 'Новости', // Основное название типа записи
                    'singular_name'      => 'Новость', // отдельное название записи типа Book
                    'add_new'            => 'Добавить новость',
                    'add_new_item'       => 'Добавить новую новость',
                    'edit_item'          => 'Редактировать новость',
                    'new_item'           => 'Новая акция',
                    'view_item'          => 'Посмотреть новость',
                    'search_items'       => 'Найти новость',
                    'not_found'          => 'Не найдено новостей',
                    'not_found_in_trash' => 'В корзине новостей не найдено',
                    'parent_item_colon'  => '',
                    'menu_name'          => 'Новости'

                  ),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'menu_icon'          => 'dashicons-info',
                'query_var'          => true,
                'rewrite'            => true,
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array('title','editor','author','thumbnail','excerpt','comments')
            ) 
        );
    }
}
new WooTheme();