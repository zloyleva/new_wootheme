<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/wooTheme/inc/walker-category.php' );
$category_products = new Category_Menu_Walker();

$args = array(
    'show_option_all'    => '',
    'show_option_none'   => __('No categories'),
    'orderby'            => 'name',
    'order'              => 'ASC',
    'style'              => 'list',
    'show_count'         => 1,
    'hide_empty'         => 0,
    'use_desc_for_title' => 1,
    'child_of'           => 0,
    'hierarchical'       => true,
    'class'              => 'nav',
    'title_li'           => __( '' ),
    'echo'               => 1,
    'current_category'   => 0,
    'pad_counts'         => 0,
    'taxonomy'           => 'product_cat',
    'walker'             => $category_products,
    'hide_title_if_empty' => false,
    'separator'          => '<br />',
);

echo '<ul class="nav category-menu">';
    wp_list_categories( $args );
echo '</ul>';
