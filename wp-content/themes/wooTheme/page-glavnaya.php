<?php get_header(); ?>

<?php
	$html = '';
	$args = array(
		'post_type'=>'sale',
		'posts_per_page' => 1
	);
	$query = new WP_Query( $args );
	
	while ( $query->have_posts() ) {
		$query->the_post();

		$excerpt = get_the_excerpt();
		$title = get_the_title();
		$bg_image = get_the_post_thumbnail_url( null, 'full' );
		$sale_url = get_the_permalink();

	}
	wp_reset_postdata();
?>

<!-- Start content by page -->
<div class="container-fluid">
	<div class="row sale_section" style="background-image:url('<?php echo $bg_image;?>') ">
		<div class="container">	
			<?php
				
				$html .= '<h2 class="text-center text-uppercase bold">';
				$html .= $excerpt;
				$html .= '</h2>';
				$html .= '<h4 class="text-center">';
				$html .= $title;
				$html .= '</h4>';
				$html .= '<p class="text-center"><a href="'.$sale_url.'" class="btn btn-sale">Узнать больше</a></p>';
				echo $html;

			?>
		</div>
	</div>
	<div class="row last_products">
		<div class="container">
			<div class="col-sm-12">
				
				<h3>Последнее поступление</h3>
				
				<ul class="products products_on_home">
					<?php
						$args = array(
							'post_type' => 'product',
							'posts_per_page' => 4,
							'orderby' => 'rand',
      						'order' => 'rand'
							);
						$loop = new WP_Query( $args );
						if ( $loop->have_posts() ) {
							while ( $loop->have_posts() ) : $loop->the_post();
								wc_get_template_part( 'content', 'product' );
							endwhile;
						} else {
							echo __( 'No products found' );
						}
						wp_reset_postdata();
					?>
				</ul><!--/.products-->

			</div>
		</div>
	</div>
	<div class="row news">
		<div class="container">
			<div class="col-sm-12">
				<h3>Новости</h3>
				<ul class="news">
					<?php
						$html = '';
						$args = array(
							'post_type'=>'news',
							'posts_per_page' => 4
						);
						$query = new WP_Query( $args );
						$html = '';

						while ( $query->have_posts() ) {
							$query->the_post();
							$excerpt = get_the_excerpt();
							$title = get_the_title();
							$bg_image = get_the_post_thumbnail_url( null, 'full' );
							$sale_url = get_the_permalink();

							$html .= '<li>';
							$html .= '<a href="'.get_the_permalink().'"">';
							$html .= '<img src="'.get_the_post_thumbnail_url( null, 'thumbnail' ).'">';
							$html .= '<span>';
							$html .= get_the_title();
							$html .= '</span>';
							$html .= '</a>';
							$html .= '</li>';

						}
						wp_reset_postdata();
						echo $html;
					?>
					
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- End content by page -->

<?php get_footer(); ?>