<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class DTWPB_Shorcodes{
	
	public function __construct(){
		
		$shortcodes = array(
			'dtwpb_single_product_image' 		=> 'dtwpb_single_product_image_sc',
			'dtwpb_single_product_title' 		=> 'dtwpb_single_product_title_sc',
			'dtwpb_single_product_rating'		=> 'dtwpb_single_product_rating_sc',
			'dtwpb_single_product_price' 		=> 'dtwpb_single_product_price_sc',
			'dtwpb_single_product_excerpt' 		=> 'dtwpb_single_product_excerpt_sc',
			'dtwpb_single_product_add_to_cart' 	=> 'dtwpb_single_product_add_to_cart_sc',
			'dtwpb_single_product_meta' 		=> 'dtwpb_single_product_meta_sc',
			'dtwpb_single_product_share' 		=> 'dtwpb_single_product_share_sc',
			'dtwpb_single_product_tabs' 		=> 'dtwpb_single_product_tabs_sc',
			'dtwpb_single_product_additional_information' => 'dtwpb_single_product_additional_information_sc',
			'dtwpb_single_product_description' 	=> 'dtwpb_single_product_description_sc',
			'dtwpb_single_product_reviews' 		=> 'dtwpb_single_product_reviews_sc',
			'dtwpb_single_product_related_products' => 'dtwpb_single_product_related_products_sc',
			'dtwpb_single_product_upsells' 		=> 'dtwpb_single_product_upsells_sc',
			
			
			'dtwpb_product_category_header' 	=> 'dtwpb_product_category_header_sc',
			'dtwpb_woocommerce_shop_loop'		=> 'dtwpb_woocommerce_shop_loop_sc',
			'dtwpb_woocommerce_sidebar' 		=> 'dtwpb_woocommerce_sidebar_sc',
			
			'dtwpb_product_grid'				=> 'dtwpb_product_grid_sc',
			
			'dtwpb_cart_table'					=> 'dtwpb_cart_table_sc',
			'dtwpb_cart_totals'					=> 'dtwpb_cart_totals_sc',
			'dtwpb_cross_sell'					=> 'dtwpb_cross_sell_sc',
			
			'dtwpb_checkout_before_customer_details' => 'dtwpb_checkout_before_customer_details_sc',
			'dtwpb_form_billing'				=> 'dtwpb_form_billing_sc',
			'dtwpb_form_shipping'				=> 'dtwpb_form_shipping_sc',
			'dtwpb_checkout_after_customer_details' => 'dtwpb_checkout_after_customer_details_sc',
			'dtwpb_checkout_order_review' 		=> 'dtwpb_checkout_order_review_sc',
			
			'dtwpb_woocommerce_myaccount_form_login'	=> 'dtwpb_woocommerce_myaccount_form_login',
			'dtwpb_woocommerce_myaccount_form_register'	=> 'dtwpb_woocommerce_myaccount_form_register',
			
			'dtwpb_woocommerce_myaccount_dashboard'	=> 'dtwpb_woocommerce_myaccount_dashboard',
			'dtwpb_woocommerce_myaccount_orders'	=> 'dtwpb_woocommerce_myaccount_orders',
			'dtwpb_woocommerce_myaccount_downloads'	=> 'dtwpb_woocommerce_myaccount_downloads',
			'dtwpb_woocommerce_myaccount_addresses'	=> 'dtwpb_woocommerce_myaccount_addresses',
			'dtwpb_woocommerce_myaccount_edit_account'	=> 'dtwpb_woocommerce_myaccount_edit_account',
			'dtwpb_woocommerce_myaccount_payment_methods'	=> 'dtwpb_woocommerce_myaccount_payment_methods',
			'dtwpb_woocommerce_myaccount_add_payment_method'	=> 'dtwpb_woocommerce_myaccount_add_payment_method',
			'dtwpb_woocommerce_myaccount_logout'	=> 'dtwpb_woocommerce_myaccount_logout',
		);
		
		/*
		 * Support YITH WooCommerce Wishlist plugin
		 */
		if ( defined( 'YITH_WCWL' ) ){
			$shortcodes['dtwpb_yith_wcwl_add_to_wishlist'] = 'dtwpb_yith_wcwl_add_to_wishlist_sc';
		}
		/*
		 * Support YITH WooCommerce Compare plugin
		 */
		if ( defined( 'YITH_WOOCOMPARE' ) ){
			$shortcodes['dtwpb_yith_woocompare_compare_button_in_product_page'] = 'dtwpb_yith_woocompare_compare_button_in_product_page_sc';
		}
		
		/*
		 * Support WooCommerce_Germanized plugin
		 */
		if( class_exists('WooCommerce_Germanized') ){
			$shortcodes['dtwpb_gzd_template_single_price_unit'] = 'dtwpb_gzd_template_single_price_unit_sc';
			$shortcodes['dtwpb_gzd_template_single_legal_info'] = 'dtwpb_gzd_template_single_legal_info_sc';
			$shortcodes['dtwpb_gzd_template_single_delivery_time_info'] = 'dtwpb_gzd_template_single_delivery_time_info_sc';
		}
		
		foreach ($shortcodes as $shortcode => $function){
			add_shortcode($shortcode, array(&$this, $function));
		}
		
	}
	
	public function dtwpb_single_product_image_sc( $atts, $content = null ){
		if( is_product() ):
			global $product;
			extract( shortcode_atts(array(
				'el_class' => '',
				'css' => '',
			), $atts) );
			ob_start();
			if(	!empty($el_class) || !empty($css) )
				echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
					if( function_exists('woocommerce_show_product_sale_flash') ){
						woocommerce_show_product_sale_flash();
					}else{
						wc_get_template( 'single-product/sale-flash.php' );
					}
					if( function_exists('woocommerce_show_product_images') ){
						woocommerce_show_product_images ();
					}else{
						wc_get_template( 'single-product/product-image.php' );
					}
			if(	!empty($el_class) || !empty($css) )
				echo '</div>';
			
			return ob_get_clean();
		endif;
	}
		
	public function dtwpb_single_product_title_sc( $atts, $content = null ){
		if( !is_product() ){
			return '<div class="woocommerce-error">Go to edit single product and select the Product Custom Page to apply the custom template for the single product page. You can also apply the custom product page for all the products in the category by go to Product / Categories / you will see the option: Product Custom Page
See the screenshot: <a href="http://take.ms/U50an" target="blank">http://take.ms/U50an</a></div>';
		}
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		woocommerce_template_single_title ();
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_rating_sc( $atts, $content = null ){
		if( is_product() ):
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			woocommerce_template_single_rating ();
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
		endif;
	}
	
	public function dtwpb_single_product_price_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			woocommerce_template_single_price ();
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_excerpt_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			woocommerce_template_single_excerpt ();
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_add_to_cart_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		woocommerce_template_single_add_to_cart ();
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_meta_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		woocommerce_template_single_meta ();
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_share_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		woocommerce_template_single_sharing ();
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_tabs_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		woocommerce_output_product_data_tabs ();
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_additional_information_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			
			if ( $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) ) {
				wc_get_template( 'single-product/tabs/additional-information.php' );
			}
			
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_description_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			
			the_content();
			
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_reviews_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			
			if(comments_open() ){
				comments_template();
			}
			
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_related_products_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		$atts = extract( shortcode_atts( array(
			'posts_per_page' => '4',
			'columns'  => '4',
			'orderby'  => 'date',
			'el_class' => '',
			'css' => '',
		), $atts ));
		
		$args = array(
			'posts_per_page' => $posts_per_page,
			'columns'        => $columns,
			'orderby'        => $orderby,
		);
		
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			
			woocommerce_related_products($args);
			
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_single_product_upsells_sc( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		global $product;
		$atts = extract( shortcode_atts( array(
			'posts_per_page' => '4',
			'columns'  => '4',
			'orderby'  => 'date',
			'el_class' => '',
			'css' => '',
		), $atts ));
		
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			
			woocommerce_upsell_display($posts_per_page, $columns, $orderby);
			
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	/*
	 * Support YITH WooCommerce Wishlist plugin
	 */
	public function dtwpb_yith_wcwl_add_to_wishlist_sc(){
		ob_start();
		echo do_shortcode('[yith_wcwl_add_to_wishlist]');
		return ob_get_clean();
	}
	/*
	 * Support YITH WooCommerce Compare plugin
	 */
	public function dtwpb_yith_woocompare_compare_button_in_product_page_sc(){
		ob_start();
		echo do_shortcode('[yith_compare_button]');
		return ob_get_clean();
	}
	
	/*
	 * Support WooCommerce_Germanized plugin
	 */
	public function dtwpb_gzd_template_single_price_unit_sc ( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		if( function_exists('woocommerce_gzd_template_single_price_unit') ){
			ob_start();
			woocommerce_gzd_template_single_price_unit();
			return ob_get_clean();
		}
	}
	public function dtwpb_gzd_template_single_legal_info_sc ( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		if( function_exists('woocommerce_gzd_template_single_legal_info') ){
			ob_start();
			woocommerce_gzd_template_single_legal_info();
			return ob_get_clean();
		}
	}
	public function dtwpb_gzd_template_single_delivery_time_info_sc ( $atts, $content = null ){
		if( !is_product() ) { return ''; }
		if( function_exists('woocommerce_gzd_template_single_delivery_time_info') ){
			ob_start();
			woocommerce_gzd_template_single_delivery_time_info();
			return ob_get_clean();
		}
	}
	
	/*
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
	 * 
	 * 
	 * 
	 * Product category page builder
	 * 
	 * 
	 * 
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
	 */
	public function dtwpb_product_category_header_sc( $atts, $content = null ){
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			?>
			<header class="woocommerce-products-header">
		
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		
					<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
		
				<?php endif; ?>
		
				<?php
					/**
					 * woocommerce_archive_description hook.
					 *
					 * @hooked woocommerce_taxonomy_archive_description - 10
					 * @hooked woocommerce_product_archive_description - 10
					 */
					do_action( 'woocommerce_archive_description' );
				?>
		
		    </header>
			<?php
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_woocommerce_shop_loop_sc( $atts, $content = null ){
		global $wp_query;
		$atts = shortcode_atts( array(
			'per_page' => '12',
			'columns'  => '4',
			'orderby'  => 'menu_order title',
			'order'    => 'asc',
			'show_pagination'    => 'show',
			'category' => '',  // Slugs
			'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
		), $atts, 'product_category' );
		
		// Get current product category
		$term = $wp_query->get_queried_object();
		$atts['category'] = $term->slug;
		
		if ( ! $atts['category'] ) {
			return '';
		}
		
		if( is_front_page() ) {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
		} else {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		}
				
		// Default ordering args
		$meta_query    = array();
		$query_args    = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
			'paged' 			  => $paged,
			'posts_per_page'      => $atts['per_page'],
			'meta_query'          => $meta_query,
		);
		
		$query_args['tax_query'][] = array(
			array(
				'taxonomy' => 'product_cat',
				'terms'    => array_map( 'sanitize_title', explode( ',', $atts['category'] ) ),
				'field'    => 'slug',
				'operator' => $atts['operator'],
			),
		);
		
		if ( isset( $atts['meta_key'] ) ) {
			$query_args['meta_key'] = $atts['meta_key'];
		}
		
		$columns                     = absint( $atts['columns'] );
		$woocommerce_loop['columns'] = $columns;
		
		$pr = new WP_Query( $query_args );
		
		// Use Main query
		$pr = $wp_query;
		$html = '';
		$pagination = '';
		
		ob_start();
		if ( $pr->have_posts() ):
		
			/**
			 * dtwpb_woocommerce_before_shop_loop hook.
			 *
			 * @hooked wc_print_notices - 10
			 */
			do_action( 'dtwpb_woocommerce_before_shop_loop' );
			?>
			<p class="woocommerce-result-count">
				<?php
				$pageds    = max( 1, $pr->get( 'paged' ) );
				$per_page = $atts['per_page'];
				$total    = $pr->found_posts;
				$first    = ( $per_page * $pageds ) - $per_page + 1;
				$last     = min( $total, $pr->get( 'posts_per_page' ) * $pageds );
			
				if ( $total <= $per_page || -1 === $per_page ) {
					/* translators: %d: total results */
					printf( _n( 'Showing the single result', 'Showing all %d results', $total, 'dt_woocommerce_page_builder' ), $total );
				} else {
					/* translators: 1: first result 2: last result 3: total results */
					printf( _nx( 'Showing the single result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'dt_woocommerce_page_builder' ), $first, $last, $total );
				}
				?>
			</p>
			
			<?php if ( function_exists( 'dtwpb_woocommerce_catalog_ordering' ) ) dtwpb_woocommerce_catalog_ordering($pr);?>
			<?php $html = ob_get_clean(); ?>
			<?php ob_start(); ?>

			<?php woocommerce_product_loop_start(); ?>
			
				<?php woocommerce_product_subcategories(); ?>

				<?php while ( $pr->have_posts() ) : $pr->the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>
			
			<?php
				if( $atts['show_pagination'] == 'show' )
					$pagination = dtwpb_woocommerce_shop_loop_pagination(array('echo'=>false),$pr);
			?>

			<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php
				/**
				 * woocommerce_no_products_found hook.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
			?>

		<?php endif; ?>
		<?php
		
		wp_reset_postdata();
		
		return $html . '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . $pagination . '</div>';
	}
	
	public function dtwpb_woocommerce_sidebar_sc( $atts, $content = null ){
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			?>
				<?php
					/**
					 * woocommerce_sidebar hook.
					 *
					 * @hooked woocommerce_get_sidebar - 10
					 */
					do_action( 'woocommerce_sidebar' );
				?>
			<?php
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_product_grid_sc($atts, $content = null){
		
		VcShortcodeAutoloader::getInstance()->includeClass( 'DTWPB_WPBakeryShortCode_VC_Product_Grid' );
		
		$product_grid_class = new DTWPB_WPBakeryShortCode_VC_Product_Grid( array( 'base' => 'vc_basic_grid' ) );
		/** @var $product_grid_class WPBakeryShortCode_VC_Basic_Grid */
		$atts = vc_map_get_attributes( $product_grid_class->getShortcode(), $atts );
		$atts['post_type'] = 'product';
		extract( $atts ); 
		
		/**
		 * Shortcode attributes
		 * @var $atts array
		 * @var $content - shortcode content
		 * Shortcode class
		 * @var $this WPBakeryShortCode_VC_Basic_Grid
		 */
		$product_grid_class->post_id = false;
		$css = $el_class = '';
		$posts = $filter_terms = array();
		$product_grid_class->buildAtts( $atts, $content );
		
		$css = isset( $atts['css'] ) ? $atts['css'] : '';
		$el_class = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
		
		$class_to_filter = 'vc_grid-container vc_clearfix wpb_content_element ' . $product_grid_class->getShortcode();
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $product_grid_class->getExtraClass( $el_class );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $product_grid_class->GetSettings('base'), $atts );
		
		wp_enqueue_script( 'prettyphoto' );
		wp_enqueue_style( 'prettyphoto' );
		
		if ( 'true' === $atts['btn_add_icon'] ) {
			vc_icon_element_fonts_enqueue( $atts['btn_i_type'] );
		}
		
		$product_grid_class->buildGridSettings();
		if ( isset( $product_grid_class->atts['style'] ) && 'pagination' === $product_grid_class->atts['style'] ) {
			wp_enqueue_script( 'twbs-pagination' );
		}
		if ( ! empty( $atts['page_id'] ) ) {
			$product_grid_class->grid_settings['page_id'] = (int) $atts['page_id'];
		}
		$product_grid_class->enqueueScripts();
		
		$animation = isset( $product_grid_class->atts['initial_loading_animation'] ) ? $product_grid_class->atts['initial_loading_animation'] : 'zoomIn';
		
		$wrapper_attributes = array();
		if ( ! empty( $atts['el_id'] ) ) {
			$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
		}
		ob_start();
		?><!-- vc_grid start -->
		<div class="dtwpb_vc_product_grid-container-wrapper vc_grid-container-wrapper vc_clearfix" <?php echo implode( ' ', $wrapper_attributes ); ?>>
			<div class="<?php echo esc_attr( $css_class ) ?>" data-initial-loading-animation="<?php echo esc_attr( $animation );?>" data-vc-<?php echo esc_attr( $product_grid_class->pagable_type ); ?>-settings="<?php echo esc_attr( json_encode( $product_grid_class->get_grid_settings() ) ); ?>" data-vc-request="<?php echo esc_attr( apply_filters( 'vc_grid_request_url', admin_url( 'admin-ajax.php' ) ) ); ?>" data-vc-post-id="<?php echo esc_attr( get_the_ID() ); ?>" data-vc-public-nonce="<?php echo vc_generate_nonce( 'vc-public-nonce' ); ?>">
			</div>
		</div><!-- vc_grid end -->
		
		<?php
		
		return ob_get_clean();
	}
	
	/*
	 * Cart page shortcodes
	 */
	public function dtwpb_cart_table_sc( $atts, $content = null ){
		if( !is_cart() ){ 
			return;
		}
		
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
			
			echo DT_WC_Shortcode_Cart::output( $atts );
			
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_cart_totals_sc( $atts, $content = null ){
		if( !is_cart() ){ return '';}
		if(WC()->cart->is_empty())
			return '';
		
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		
		echo '<div class="woocommerce '.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		
			woocommerce_cart_totals();
		
		echo '</div>';

		return ob_get_clean();
	}
	
	public function dtwpb_cross_sell_sc( $atts, $content = null ){
		if( !is_cart() ){ return '';}
		extract( shortcode_atts(array(
			'posts_per_page'=> 4,
			'columns'=> 4,
			'orderby'=> 'rand',
			'el_class' => '',
			'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		?>

			<?php woocommerce_cross_sell_display( $posts_per_page, $columns, $orderby); ?>
		
		<?php
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';

		return ob_get_clean();
	}
	
	/*
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *
	 * Checkout page builder
	 *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 */
	public function dtwpb_checkout_before_customer_details_sc( $atts, $content = null ){
		if ( !is_checkout() ) { 
			return;
		}
		
		$checkout = WC()->checkout();
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		if ( sizeof( $checkout->checkout_fields ) > 0 ) :
		do_action( 'woocommerce_checkout_before_customer_details' );
		endif;
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
	
		return ob_get_clean();
	}
	
	public function dtwpb_form_billing_sc( $atts, $content = null ){
		if ( !is_checkout() ) {
			return;
		}
		
		$checkout = WC()->checkout();
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		if ( sizeof( $checkout->checkout_fields ) > 0 ) :
		do_action( 'woocommerce_checkout_billing' );
		endif;
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
	
		return ob_get_clean();
	}
	
	public function dtwpb_form_shipping_sc( $atts, $content = null ){
		if ( !is_checkout() ) {
			return;
		}
		
		$checkout = WC()->checkout();
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		if ( sizeof( $checkout->checkout_fields ) > 0 ) :
		do_action( 'woocommerce_checkout_shipping' );
		endif;
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
	
		return ob_get_clean();
	}
	
	public function dtwpb_checkout_after_customer_details_sc( $atts, $content = null ){
		if ( !is_checkout() ) {
			return;
		}
		
		$checkout = WC()->checkout();
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		if ( sizeof( $checkout->checkout_fields ) > 0 ) :
		do_action( 'woocommerce_checkout_after_customer_details' );
		endif;
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
	
		return ob_get_clean();
	}
	
	public function dtwpb_checkout_order_review_sc( $atts, $content = null ){
		if ( !is_checkout() ) {
			return;
		}
		
		$checkout = WC()->checkout();
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';?>
			
			<h3 id="order_review_heading"><?php _e( 'Your order', 'dt_woocommerce_page_builder' ); ?></h3>
		
			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
		
			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>
		
			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		<?php
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
		
	
	/*
	 * Custom MyAccount before login page
	 */
	
	public function dtwpb_woocommerce_myaccount_form_login( $atts, $content = null ){
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		
		wc_get_template( 'myaccount/form-login.php', array( 'woocommerce-page-builder-custom-templates' => 1 ), DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/', DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/' );
		
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_woocommerce_myaccount_form_register( $atts, $content = null ){
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		
		wc_get_template( 'myaccount/form-register.php', array( 'woocommerce-page-builder-custom-templates' => 1 ), DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/', DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/' );
		
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	/*
	 * Custom MyAccount page
	 */
	public function dtwpb_woocommerce_myaccount_dashboard( $atts, $content = null ){
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		
		if( !empty($content) ){
			global $current_user;
			?>
			<?php if( is_user_logged_in() ) : ?>
			<p>
				<?php
					printf(
					__( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'dt_woocommerce_page_builder' ),
					'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
					esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) )
					);
				?>
			</p>
			<?php endif; ?>
			<p>
				<?php echo $content;?>
			</p>
			<?php
		}else{
			wc_get_template( 'myaccount/dashboard.php', array(
			'current_user' => get_user_by( 'id', get_current_user_id() ),
			) );
		}
		
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_woocommerce_myaccount_orders( $atts, $content = null ){
		if ( ! is_user_logged_in() ) { return esc_html__('You need first to be logged in', 'dt_woocommerce_page_builder'); }
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
			ob_start();
			if(	!empty($el_class) || !empty($css) )
				echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		
		global $wp,$dtwbp_my_account_current_order_id,$dtwpb_wc_get_endpoint_url_tab_id;
		
		VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Section' );
		$tab_id = end(WPBakeryShortCode_VC_Tta_Section::$section_info);
		if($tab_id){
			do_action('dtwpb_account_orders_in_tab',$tab_id);
			do_action('dtwpb_wc_get_endpoint_url',$tab_id);
		}
		
		if( isset($wp->query_vars['orders']) ){
			$value = $wp->query_vars['orders'];
			do_action( 'woocommerce_account_orders_endpoint', $value );
			
		}elseif( isset($wp->query_vars['view-order']) ){
			$value = $wp->query_vars['view-order'];
			if($tab_id){
				$myaccount_url = get_permalink().'#'.$tab_id['tab_id'];
				do_action('dtwpb_woocommerce_account_view_order_backorder',$myaccount_url);
			}
			do_action( 'woocommerce_account_view-order_endpoint', $value );
			
		}elseif( isset($wp->query_vars['payment-methods']) ){
			$value = $wp->query_vars['payment-methods'];
			do_action( 'woocommerce_account_view-order_endpoint', $value );
			
		}elseif( isset($wp->query_vars['add-payment-method']) ){
			$value = $wp->query_vars['add-payment-method'];
			do_action( 'woocommerce_account_view-order_endpoint', $value );
			
		}else{
			$value = '';
			do_action( 'woocommerce_account_orders_endpoint', $value );
		}
		/*
		foreach ( $wp->query_vars as $key => $value ) {
			// Ignore pagename param.
			if ( 'pagename' === $key ) {
				continue;
			}

			if ( has_action( 'woocommerce_account_' . $key . '_endpoint' ) ) {
				do_action( 'woocommerce_account_' . $key . '_endpoint', $value );
				return;
			}
		}*/
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		$dtwbp_my_account_current_order_id = null;
		$dtwpb_wc_get_endpoint_url_tab_id = null;
		return ob_get_clean();
	}
	
	public function dtwpb_woocommerce_myaccount_downloads( $atts, $content = null ){
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
			ob_start();
			if(	!empty($el_class) || !empty($css) )
				echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		
		do_action('woocommerce_account_downloads_endpoint');
		
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_woocommerce_myaccount_addresses( $atts, $content = null ){
		if ( !is_account_page() ) {
			return;
		}
		
		if ( ! is_user_logged_in() ) { return esc_html__('You need first to be logged in', 'dt_woocommerce_page_builder'); }
		global $wp, $dtwpb_wc_get_endpoint_url_tab_id;
		
		$type = '';
		
		if( isset($wp->query_vars['edit-address']) ){
			$type = $wp->query_vars['edit-address'];
		}else{
			$type = wc_edit_address_i18n( sanitize_title( $type ), true );
		}
		
		VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Section' );
		$tab_id = end(WPBakeryShortCode_VC_Tta_Section::$section_info);
		if($tab_id){
			do_action('dtwpb_wc_get_endpoint_url',$tab_id);
		}
		
		
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
			), $atts) );
			ob_start();
			if(	!empty($el_class) || !empty($css) )
				echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		
		WC_Shortcode_My_Account::edit_address( $type );
		
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		$dtwpb_wc_get_endpoint_url_tab_id = null;
		return ob_get_clean();
	}
	
	public function dtwpb_woocommerce_myaccount_edit_account( $atts, $content = null ){
		if ( !is_account_page() ) {
			return;
		}
		
		if ( ! is_user_logged_in() ) { return esc_html__('You need first to be logged in', 'dt_woocommerce_page_builder'); }
		extract( shortcode_atts(array(
		'el_class' => '',
		'css' => '',
		), $atts) );
		ob_start();
		if(	!empty($el_class) || !empty($css) )
			
				echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		
				do_action('woocommerce_account_edit-account_endpoint');
		
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_woocommerce_myaccount_payment_methods( $atts, $content = null ){
		if ( !is_account_page() ) {
			return;
		}
		
		if ( ! is_user_logged_in() ) { return esc_html__('You need first to be logged in', 'dt_woocommerce_page_builder'); }
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
			), $atts) );
			ob_start();
			if(	!empty($el_class) || !empty($css) )
				echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
				
				//do_action('woocommerce_account_payment-methods_endpoint');
				
				//wc_get_template( 'myaccount/payment-methods.php' );
				
				wc_get_template( 'myaccount/payment-methods.php', array( 'woocommerce-page-builder-custom-templates' => 1 ), DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/', DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/' );
			
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	
	public function dtwpb_woocommerce_myaccount_add_payment_method( $atts, $content = null ){
		if ( !is_account_page() ) {
			return;
		}
		
		if ( ! is_user_logged_in() ) { return esc_html__('You need first to be logged in', 'dt_woocommerce_page_builder'); }
		
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
			), $atts) );
			ob_start();
			if(	!empty($el_class) || !empty($css) )
				echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
				
				//WC_Shortcode_My_Account::add_payment_method();
				//do_action('woocommerce_account_add-payment-method_endpoint');
				//wc_get_template( 'myaccount/form-add-payment-method.php' );
		
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
	public function dtwpb_woocommerce_myaccount_logout($atts, $content = null){
		if ( ! is_user_logged_in() ) { return ''; }
		extract( shortcode_atts(array(
			'el_class' => '',
			'css' => '',
		), $atts) );
			ob_start();
			if(	!empty($el_class) || !empty($css) )
				echo '<div class="'.esc_attr($el_class). dtwpb_woocommerce_page_builder_shortcode_vc_custom_css_class($css, ' ').'">';
		
			foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
				if( $endpoint == 'customer-logout' ):
				?>
				<a href="<?php echo esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php echo esc_html( $label ); ?></a>
				
				<?php
				endif;
			endforeach;
		
		if(	!empty($el_class) || !empty($css) )
			echo '</div>';
		
		return ob_get_clean();
	}
	
}
new DTWPB_Shorcodes();


/**
 * Cart Shortcode
 * DT_WC_Shortcode_Cart
 * Used on the cart page, the cart shortcode displays the cart contents and interface for coupon codes and other cart bits and pieces.
 *
 * @author 		WooThemes
 * @category 	Shortcodes
 * @package 	WooCommerce/Shortcodes/Cart
 * @version     2.3.0
 */
class DT_WC_Shortcode_Cart extends WC_Shortcode_Cart{
	/**
	 * Output the cart shortcode.
	 */
	public static function output( $atts = '' ) {
		// Constants.
		wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

		$atts = shortcode_atts( array(), $atts, 'woocommerce_cart' );

		// Update Shipping
		if ( ! empty( $_POST['calc_shipping'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'woocommerce-cart' ) ) {
			self::calculate_shipping();

			// Also calc totals before we check items so subtotals etc are up to date
			WC()->cart->calculate_totals();
		}

		// Check cart items are valid
		do_action( 'woocommerce_check_cart_items' );

		// Calc totals
		WC()->cart->calculate_totals();

		if ( WC()->cart->is_empty() ) {
			wc_get_template( 'cart/cart-empty.php',  array('woocommerce-page-builder-custom-templates' => 1), DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/', DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/' );
		} else {
			wc_get_template( 'cart/cart.php',  array('woocommerce-page-builder-custom-templates' => 1), DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/', DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/'  );
		}
	}
}
