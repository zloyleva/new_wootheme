<?php

/**
* 
*/
class WriteProduct
{
	public $wpdb_global;
	public $taxonomy = 'product_cat';
    public $post_status = 'publish';
    public $post_type = 'product';
    public $product_type_name = 'simple';
    public $product_type = 'product_type';

	function __construct()
	{
		global $wpdb;
		$this->wpdb_global = $wpdb;
        require_once( plugin_dir_path( __FILE__ ) . 'addPicture.php');
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
	}

	function to_write_product($product){

		// Statistic data
		$returned_data = [];
		$status_product = ''; // Update or insert

		if(!isset($product) || $product == false){
			return $returned_data['error'] = 'We don"t got product!';
		}

		$product_cat_id = $this->get_category_id($product['categories']);
		if( isset($product_cat_id['error']) ) {
			$returned_data['SKU_product_fail'] = $product['sku'];
			$returned_data['error'] = $product_cat_id['error'];
			return $returned_data;
		}

		// Insert(update) product
		$product_id = $this->is_exist_product( $product );
		if( $product_id ):
            //Update product
            $product_id = $this->insert_product( $product, $product_id );
        	$returned_data['status_insert'] = 'update. $product_id = '.$product_id;
        else:
            //Insert product
            $product_id = $this->insert_product( $product );
        	$returned_data['status_insert'] = 'insert. $product_id ='.$product_id;
        endif;

        if( $product_id ) :
            $this->set_productmeta( $product_id, $product, $product_cat_id['ID'] );
            $returned_data['attach'] = ProductImage::pass_image( $product['sku'], $product_id );// Todo add image
        else:
            $returned_data['error'] = 'Error insert/update product. Don"t get $product_id';//Error insert/update product
        endif;

		return $returned_data;
	}

	function is_exist_product( $product ){
 		$sql_find = "SELECT post_id FROM wp_postmeta WHERE meta_key='_sku' AND meta_value='{$product['sku']}'";
        $result = $this->wpdb_global->get_var( $sql_find );
        return $result; 
	}

    function insert_product( $product, $product_id = 0 ){
        $product_args = array(
            'post_title'    =>  $product['name'],
            'post_content'  =>  ( isset($product['content']) )?$product['content']:'',
            'post_excerpt'  =>  ( isset($product['short_desc']) )?$product['short_desc']:'',
            'post_status'   =>  $this->post_status,
            'post_type'     =>  $this->post_type
        );
        if( $product_id ){
            $product_args['ID'] = $product_id;
        }
        $post_id = wp_insert_post( $product_args );
        return $post_id;
    }

    function get_category_id($cat_array){
        $parent_id = 0;
        $current_item = 0;
        for ($i = 0; $i < count($cat_array); $i++){
            $check_item = term_exists( $cat_array[$i], $this->taxonomy, $parent_id );
            if($check_item):
                // 'Category is exists
                $parent_id = $check_item['term_id'];
            else:
                // Category isn't exists. Created new.
                $cat_params = array(
                    'cat_name' => $cat_array[$i],           // Taxonomy name
                    'category_parent' => $parent_id,        // ID parent category
                    'taxonomy' => $this->taxonomy                 // Taxonomy type
                );
                $parent_id = wp_insert_category( $cat_params, true );
                if(!$parent_id){
                	return ['error' => 'We has fail after insert category'];
                }
            endif;
            $current_item = $parent_id;
        }
        //Return ID current category
        return [ 'ID' => $current_item];
    }

    function set_productmeta( $post_id, $product, $product_cat_id = 0 ){

        wp_set_object_terms($post_id, $this->product_type_name, $this->product_type);
        wp_set_object_terms($post_id,(integer) $product_cat_id, $this->taxonomy);

        update_post_meta($post_id, '_visibility', 'visible');
        update_post_meta($post_id, '_stock_status', 'instock');
        update_post_meta($post_id, 'total_sales', '0');
        update_post_meta($post_id, '_downloadable', 'no');
        update_post_meta($post_id, '_virtual', 'no');
        update_post_meta($post_id, '_regular_price', $product['price']);
        update_post_meta($post_id, '_sale_price', '');
        update_post_meta($post_id, '_purchase_note', '');
        update_post_meta($post_id, '_featured', 'no');
        update_post_meta($post_id, '_weight', '');
        update_post_meta($post_id, '_length', '');
        update_post_meta($post_id, '_width', '');
        update_post_meta($post_id, '_height', '');
        update_post_meta($post_id, '_sku', $product['sku']);
        update_post_meta($post_id, '_product_attributes', array());
        update_post_meta($post_id, '_sale_price_dates_from', '');
        update_post_meta($post_id, '_sale_price_dates_to', '');
        update_post_meta($post_id, '_price', $product['price']);
        update_post_meta($post_id, '_sold_individually', '');
        update_post_meta($post_id, '_manage_stock', 'yes');
        update_post_meta($post_id, '_backorders', 'no');
        update_post_meta($post_id, '_stock', $product['stock']);
    }
}