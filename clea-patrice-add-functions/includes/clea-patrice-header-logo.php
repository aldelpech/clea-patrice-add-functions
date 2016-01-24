<?php
/**
 *
 * Load plugin templates or look for identical template names in the theme directory 
 *
 *
 * @link       	http://parcours-performance.com/anne-laure-delpech/#ald
 * @since      	1.0.0
 *
 * @package    clea-patrice-add-functions
 * @subpackage clea-patrice-add-functions/includes
 */

 

add_action( 'init', 'clea_patrice_storefront_site_branding' );


/***************************************************
* * add a logo to the default storefront theme
* see https://docs.woothemes.com/document/add-a-custom-logo/
***************************************************/
if ( ! function_exists( 'clea_patrice_storefront_site_branding' ) ) {

	function clea_patrice_storefront_site_branding() {

		if ( function_exists( 'storefront_site_branding' ) ) {
			remove_action( 'storefront_header', 'storefront_site_branding',			20 );
		}	

		add_action( 'storefront_header', 'clea_storefront_display_custom_logo', 20 );
	}
}


function clea_storefront_display_custom_logo() {
	?>		
				
	<div class="site-branding">
		<?php if ( function_exists( 'jetpack_the_site_logo' ) ) jetpack_the_site_logo(); ?>
		<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php if ( '' != get_bloginfo( 'description' ) ) { ?>
			<p class="site-description"><?php echo bloginfo( 'description' ); ?></p>
		<?php } ?>
	</div>
	<?php

}



?>	