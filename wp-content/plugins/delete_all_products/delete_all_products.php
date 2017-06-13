<?php
/*
Plugin Name: Delete all products
Plugin URI: http://wordpress.org/plugins/my-plugin/
Description: This is a plugin for deleted all products from WooCommerce + categories
Author: ZloyLeva
Version: 0.2.0
Author URI: http://localhost/
*/


class DeleteAllProducts {

    public $db;
    function __construct(){
        global $wpdb;
        $this->db = $wpdb;

    	// Register heandler for Ajax callback
        add_action( 'wp_ajax_call_delete_products', array( $this, 'delete_all_products' ) );

        add_action( 'admin_init', array( $this, 'my_plugin_admin_init' ) );
        add_action( 'admin_menu', array( $this, 'add_item_to_admin_page') );
    }
    
    //Register scripts on create object
	function my_plugin_admin_init() {
		wp_register_script( 'delete-all-product', plugins_url( '/js/delete-all-product.js', __FILE__ ), array('jquery'), '1.0.1', true );
	}

	// Init menu's item on admin page
    function add_item_to_admin_page(){
		$page = add_menu_page( 'On this page you can delete all products', 'Delete all products', 'manage_options', 'delete_products', array( $this, 'add_my_setting'), 'dashicons-trash', 4.1 ); 
		// Load script on plugin's page
		add_action( 'admin_print_scripts-' . $page, array( $this, 'load_required_scripts') );
	}

    // Connect script
    function load_required_scripts() {
        wp_enqueue_script( 'delete-all-product' );
    }
    
    // It's AJAX call function for delete all products and categories
    function delete_all_products(){

        $post_type = 'product';
        $product_cat = 'product_cat';

        if( !(current_user_can('administrator') ) ){
            echo 'Error - you don"t has permission';
        }

        // Prepare query for SQL

        $sql_00 = "DELETE FROM {$this->db->postmeta} WHERE post_id IN (SELECT ID FROM {$this->db->posts} where post_parent IN (SELECT ID FROM {$this->db->posts} WHERE post_type = '%s'))";
        $sql_0 = "DELETE FROM {$this->db->posts} where post_parent IN (SELECT ID FROM {$this->db->posts} WHERE post_type = '%s')";

        $sql_1 = "DELETE FROM {$this->db->term_relationships} WHERE object_id IN (SELECT ID FROM {$this->db->posts} WHERE post_type = '%s')";
        $sql_2 = "DELETE FROM {$this->db->postmeta} WHERE post_id IN (SELECT ID FROM {$this->db->posts} WHERE post_type = '%s')";
        $sql_3 = "DELETE FROM {$this->db->posts} WHERE post_type = '%s'";
        $sql_4 = "DELETE FROM {$this->db->terms} WHERE term_id IN (SELECT term_id FROM {$this->db->term_taxonomy} WHERE taxonomy='%s')";
        $sql_5 = "DELETE FROM {$this->db->term_taxonomy} WHERE taxonomy='%s'";

        // Do SQL query
        $x00 = $this->db->query( $this->db->prepare( $sql_00, $post_type) );
        $x0 = $this->db->query( $this->db->prepare( $sql_0, $post_type) );

        $x1 = $this->db->query( $this->db->prepare( $sql_1, $post_type) );
        $x2 = $this->db->query( $this->db->prepare( $sql_2, $post_type) );
        $x3 = $this->db->query( $this->db->prepare( $sql_3, $post_type) );
        $x4 = $this->db->query( $this->db->prepare( $sql_4, $product_cat) );
        $x5 = $this->db->query( $this->db->prepare( $sql_5, $product_cat) );

        echo "Deleted: {$x3} products and {$x4} categories";
        wp_die();
    }

    
    /**
	* Create page tamplate for admin page
	*/
    function add_my_setting(){
		?>
		<div class="wrap">
			<h2><?php echo get_admin_page_title() ?></h2>

			<?php
			// settings_errors() не срабатывает автоматом на страницах отличных от опций
			if( get_current_screen()->parent_base !== 'options-general' )
				settings_errors('название_опции');
			?>

			<form action="" method="POST" class="form-delete_all_products">
				<?php
					settings_fields("opt_group");     // скрытые защитные поля
					do_settings_sections("opt_page"); // секции с настройками (опциями).
					submit_button('Delete all products');
				?>
			</form>
			<div class="show_results"></div>
		</div>
		<?php
	}

}
new DeleteAllProducts();

