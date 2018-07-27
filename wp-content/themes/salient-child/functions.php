<?php 

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {
	
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));

    if ( is_rtl() ) 
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
}

add_action( 'woocommerce_product_thumbnails', 'wc_print_noticeslll', 10 );
 
function wc_print_noticeslll(){
global $product;

if (  method_exists( $product, 'get_type') && $product->get_type() == 'auction' ) :
	
	$user_id  = get_current_user_id();
    echo "<style>span.winning{top: 0px !important;left: 0px !important;}</style>";
    
	if ( is_user_logged_in() && $user_id == $product->get_auction_current_bider() && !$product->get_auction_closed() && !$product->is_sealed()) :
	    
		echo apply_filters('woocommerce_simple_auction_winning_bage', '<span class="winning" data-auction_id="'.$product->get_id().'" data-user_id="'.get_current_user_id().'">'.__( 'Winning!', 'wc_simple_auctions' ).'</span>', $product);

	endif; 
endif;

}


?>