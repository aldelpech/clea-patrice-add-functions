<?php
/**
 *
 * Various functions for use in the childtheme boutique of the woocommerce StoreFront theme 
 *
 *
 * @link       	http://parcours-performance.com/anne-laure-delpech/#ald
 * @since      	1.2.0
 *
 * @package    clea-patrice-add-functions
 * @subpackage clea-patrice-add-functions/includes
 */

 
add_action( 'wp_loaded', 'clea_patrice_show_img_cat');
add_action( 'init', 'clea_patrice_remove_storefront_header_search' );
add_action( 'storefront_header', 'clea_patrice_storefront_header_content', 40 );



/***************************************************
* * show product categories for images
***************************************************/
// Requires 2 plugins : enhanced media category and Woocommerce

function clea_patrice_show_img_cat() {

	// see https://wordpress.org/support/topic/product-category-in-the-library-dashboard

	global $wp_taxonomies;

    foreach ( get_taxonomies_for_attachments( 'object' ) as $taxonomy => $params ) {

        if ( in_array( 'attachment', $params->object_type ) && in_array( 'product', $params->object_type ) ) {

            // turns on taxonomy columns
            $wp_taxonomies[$taxonomy]->show_admin_column = 1;
        }
    }
	
}	

/***************************************************
* * remove search bar

* http://www.pootlepress.com/2015/02/21-tips-tricks-and-css-tweaks-for-woothemes-storefront/
* #12 tip
***************************************************/

function clea_patrice_remove_storefront_header_search() {
	remove_action( 'storefront_header', 'storefront_product_search', 	40 );
}


/***************************************************
* add text to the header

* https://docs.woothemes.com/document/add-static-content-to-the-storefront-header/

***************************************************/

function clea_patrice_storefront_header_content() { ?>
	<div class="clea-adresse">
		<p>Vous avez une question ? </p> 
		<p><em>Contactez-moi (Patrice Poiraud) :</em></p> 
		<p> <strong>06 84 32 51 31</strong> ou <strong><?php echo antispambot('graph@bwatbase.com'); ?></strong></p> 
	</div>
	<?php
}

/***************************************************
* change text in 'add to cart' or 'read more' button

* https://community.theme.co/forums/topic/read-more-button-on-woocommerce/
* https://docs.woothemes.com/document/change-add-to-cart-button-text/

***************************************************/


add_filter( 'woocommerce_product_add_to_cart_text', 'clea_patrice_sold_cart_button_text' );    // 2.1 +
 
function clea_patrice_sold_cart_button_text() {

$custom_fields = get_post_custom( $product->ID );
$term = $custom_fields['_stock_status'][0] ; // 'outofstock' or 'instock'


	if ( "outofstock" == $term ) {
		return __( 'Détails', 'woocommerce' );
	} else {
		return __( 'Ajouter au Panier', 'woocommerce' );		
	}

}
?>