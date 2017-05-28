<?php get_header(); ?>
<?php
	global $wp_query;
	$breadcrumb_args = array(
		'home' => 'Главная',
		'delimiter' => ' / ',
	);
?>

<div class="container-fluid">

	<div class="row woocommerce_breadcrumb">
		<div class="container">
			<div class="col-sm-12">
				<?php woocommerce_breadcrumb( $breadcrumb_args ); ?>			
			</div>
		</div>
	</div>
	<div class="container">
    <?php 

    	if ( is_singular( 'product' ) ) {

			while ( have_posts() ) : the_post();

				wc_get_template_part( 'content', 'single-product' );

			endwhile;

		} else { ?>

			<?php do_action( 'woocommerce_archive_description' ); ?>


		<div class="col-sm-3 categories_menu_section">
			<!-- Start Leftside menu. Show products categories -->

			<?php

	            $overridden_template = locate_template('templates/woo-category-menu.php');
	            load_template( $overridden_template );

	        ?>

			<!-- End  Leftside menu. Show products categories -->
		</div>

		<div class="col-sm-9">
			<!-- Start show products -->

				<?php if ( have_posts() ) : ?>

					<?php do_action( 'woocommerce_before_shop_loop' ); ?>

					<?php woocommerce_product_loop_start(); ?>

						<?php woocommerce_product_subcategories(); ?>

						<?php while ( have_posts() ) : the_post(); ?>

							<?php wc_get_template_part( 'content', 'product' ); ?>

						<?php endwhile; // end of the loop. ?>

					<?php woocommerce_product_loop_end(); ?>

					<?php do_action( 'woocommerce_after_shop_loop' ); ?>

				<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

					<?php do_action( 'woocommerce_no_products_found' ); ?>

				<?php endif;?>

			<!-- End show products -->
		</div>
		

<?php	}?>
  
	</div>
</div>

<script>
    (function($){
        $(function () {

            //Menu category list
            $( document ).ready(function () {

            	var currentTree = $('.current-cat').parents('.cat-item');

                $.each( $(currentTree), function (i, obj) {
                    $(obj).addClass( 'active-item' );
                });
            });
           
        });
    })(jQuery)
</script>

<?php get_footer(); ?>
