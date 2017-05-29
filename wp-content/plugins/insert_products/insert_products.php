<?php
/*
Plugin Name: Insert products to WooCommerce from CSV
Plugin URI: http://wordpress.org/plugins/my-plugin/
Description: This is a plugin for Insert products to WooCommerce from CSV + and add categories
Author: ZloyLeva
Version: 0.2.0
Author URI: http://localhost/
*/


class InsertProducts{

	
	public $db;
	public $upload_dir;

    function __construct(){
    	ini_set('error_reporting', E_ALL);
    	ini_set('display_errors', 1);
    	ini_set('max_input_time', 600);
    	ini_set('post_max_size', 2);

        global $wpdb;
        $this->db = $wpdb;
        $this->upload_dir = wp_upload_dir();
        // Register heandler for Ajax callback
        add_action( 'wp_ajax_call_upload_price', array( $this, 'upload_price' ) );
        add_action( 'wp_ajax_call_read_price_file', array( $this, 'read_price_file' ) );
        add_action( 'wp_ajax_call_insert_products', array( $this, 'insert_products' ) );

        add_action( 'admin_init', array( $this, 'register_require_scripts' ) );
        add_action( 'admin_menu', array( $this, 'add_field_to_admin_page') );

        require_once( plugin_dir_path( __FILE__ ) . 'inc/getfile.php');
        require_once( plugin_dir_path( __FILE__ ) . 'inc/productParcer.php');
        require_once( plugin_dir_path( __FILE__ ) . 'inc/write_product.php');
        

        error_reporting(E_ALL);
        ini_set('display_errors',1);
    }

    //Register scripts on create object
	function register_require_scripts() {
		wp_register_script( 'insert-product', plugins_url( '/js/insert-product.js', __FILE__ ), array('jquery'), '1.0.1', true );
	}

    // Init menu's item on admin page
    function add_field_to_admin_page(){
		$page = add_menu_page( 'On this page you can add products to your store', 'Add products', 'manage_options', 'insert_products', array( $this, 'add_products_field'), 'dashicons-cart', 4 ); 
		add_action( 'admin_print_scripts-' . $page, array( $this, 'load_required_scripts') );
	}

	// Connect script
    function load_required_scripts() {
        wp_enqueue_script( 'insert-product' );
    }

	/**
	* Create page tamplate for admin page
	*/
    function add_products_field(){
		?>
		<div class="wrap">
			<h2><?php echo get_admin_page_title() ?></h2>

			<?php
			// settings_errors() не срабатывает автоматом на страницах отличных от опций
			if( get_current_screen()->parent_base !== 'options-general' )
				settings_errors('название_опции');
			?>

			<form action="" method="POST" class="form-add_products">

				<input type="file" class="get_price_file">
				<?php
					settings_fields("opt_group");     // скрытые защитные поля
					do_settings_sections("opt_page"); // секции с настройками (опциями).
					submit_button('Upload price');
				?>
			</form>
			<div class="show_results"></div>
		</div>
		<?php
	}

	/**
	* Upload xls price and send data about file
	*/
	function upload_price(){
    	// Upload file to server. Check errors on upload
    	if($_FILES['file']['error']){
    		echo "Error: {$_FILES['file']['error']}";
    		wp_die();
    	}
    	//Move file from temp storage to uploads' dir
    	$upload_status = move_uploaded_file($_FILES['file']['tmp_name'], $this->upload_dir['basedir'] . '/' . $_FILES['file']['name']);

    	echo json_encode([
	    		'status' => $upload_status,
	    		'file_name' => $_FILES['file']['name'],
	    		'dir'	=> $_FILES['file'],
    		]);
    	wp_die();
    }

	/**
	* Read and parsed price file
	*/
    function read_price_file(){

		if(isset($_POST['file'])){

			//Get file name
		    $file = $_POST['file'];
			$excel_file = $this->upload_dir['basedir'] . '/' . $file;

			// Get data from excel file to array
			$inputFileType = GetPriceFileData::read_price($excel_file);

			$return_data = productParser::parce_data($inputFileType);

			echo json_encode($return_data);
			wp_die();

		}

    	wp_die();
    }

    function insert_products(){

    	$write_product = new WriteProduct;
    	if(!isset($_POST['product']) || $_POST['product'] == false){
    		$result['error'] = 'We don"t got $_POST[product]';
    	}else{
    		$result = $write_product->to_write_product($_POST['product']);
    	}

    	echo json_encode($result);

    	wp_die();
    }

}
new InsertProducts();