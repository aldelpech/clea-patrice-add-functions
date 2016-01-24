<?php
/**
 *
 * 
 *
 *
 * @link       	http://parcours-performance.com/anne-laure-delpech/#ald
 * @since      	1.2.0
 *
 * @package    clea-patrice-add-functions
 * @subpackage clea-patrice-add-functions/includes
 */

 
add_action( 'woocommerce_product_query', 'clea_patrice_instock_product_query' );

function clea_patrice_instock_product_query( $q ){

	// inspiration http://www.kathyisawesome.com/woocommerce-modifying-product-query/
	
    $product_ids_in_stock = clea_patrice_get_in_stock_product_ids();

    $meta_query = WC()->query->get_meta_query();

    $q->set( 'post__in', array_merge( array( 0 ), $product_ids_in_stock ) );

} 
 
function clea_patrice_get_in_stock_product_ids() {

	// inspiré de la fonction wc_get_product_ids_on_sale (woocommerce/includes/wc-product-functions.php)
	$in_stock = get_posts( array(
		'post_type'      => array( 'product', 'product_variation' ),
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_query'     => array(
			array(
				'key' 		=> '_visibility',
				'value' 	=> array('catalog', 'visible'),
				'compare' 	=> 'IN'
			),
 			array(
				'key' => '_stock_status',
				'value' => 'instock',
				'compare' => '='
			)
		),
		'fields' => 'id=>parent'
	) );

	$product_ids          = array_keys( $in_stock );
	$parent_ids           = array_values( array_filter( $in_stock ) );
	$in_stock_product_ids = array_unique( array_merge( $product_ids, $parent_ids ) );

	return $in_stock_product_ids;
} 
 



/*
* a shortcode to display sold out products
* 
* source snippet #20 Display onsale products catalog shortcode
* http://www.wpexplorer.com/best-woocommerce-snippets/
*/


function clea_patrice_sold_out_shortcode( $atts ) {

    global $woocommerce_loop;

    extract(shortcode_atts(array(
        'per_page'  => '12',
        'columns'   => '3',
        'orderby' => 'date',
        'order' => 'desc'
    ), $atts));

    $woocommerce_loop['columns'] = $columns;

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts'   => 1,
        'posts_per_page' => $per_page,
        'orderby' => $orderby,
        'order' => $order,
        'meta_query' => array(
			array(
				'key' 		=> '_visibility',
				'value' 	=> array('catalog', 'visible'),
				'compare' 	=> 'IN'
			),
			array(
				'key' => '_stock_status',
				'value' => 'outofstock',
				'compare' => '='
			)
        )
    );
	
	// Buffer our contents
	ob_start();
		do_action( 'woocommerce_before_shop_loop' );
		$loop = new WP_Query( $args );
			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) : $loop->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
			} else {
				echo __( 'No products found' );
			}
		wp_reset_postdata();
		do_action( 'woocommerce_after_shop_loop' );
	// Return buffered contents
    return '<ul class="products">' . ob_get_clean() . '</ul>';
}

add_shortcode('produits_vendus', 'clea_patrice_sold_out_shortcode');


add_action( 'woocommerce_before_shop_loop_item_title', 'clea_patrice_soldout_badge' );

function clea_patrice_soldout_badge() {
	
    global $product;
 
    if ( !$product->is_in_stock() ) {
        echo '<span class="soldout">VENDU</span>';
    }
} 

add_filter('woocommerce_stock_html', 'change_stock_message', 10, 2);
function change_stock_message($message, $stock_status) {
    if ($stock_status == "Produit épuisé") {
        $message = '<p class="stock out-of-stock">Vendu</p>';    
    } else {
        $message = '<p class="stock in-stock">Disponible</p>';           
    }
    return $message;
}
