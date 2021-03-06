<?php
/**
 * The template for displaying the Product edit form  
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/
 *
 * @package    WCVendors_Pro
 * @version    1.3.10 
 */

/**
 *   DO NOT EDIT ANY OF THE LINES BELOW UNLESS YOU KNOW WHAT YOU'RE DOING 
 *   
*/

$title 		= ( is_numeric( $object_id ) ) ? __('Save Changes', 'wcvendors-pro') : __('Add Listing', 'wcvendors-pro'); 
$product 	= ( is_numeric( $object_id ) ) ? wc_get_product( $object_id ) : null;
$post 		= ( is_numeric( $object_id ) ) ? get_post( $object_id ) : null;

// Get basic information for the product 
$product_title     			= ( isset($product) && null !== $product ) ? $product->get_title()    : ''; 
$product_description        = ( isset($product) && null !== $product ) ? $post->post_content  : ''; 
$product_short_description  = ( isset($product) && null !== $product ) ? $post->post_excerpt  : ''; 
$post_status				= ( isset($product) && null !== $product ) ? $post->post_status   : ''; 

/**
 *  Ok, You can edit the template below but be careful!
*/
?>

<h2><?php echo $title; ?></h2>
<style>
.sexlabel{
	padding:0px 10px 0px 24px;font-size: 18px;margin-top: -8px;
}
</style>
<!-- Product Edit Form -->
<form method="post" action="" id="wcv-product-edit" class="wcv-form wcv-formvalidator"> 

	<!-- Basic Product Details -->
	<div class="wcv-product-basic wcv-product"> 
		<!-- Product Title -->
		<?php WCVendors_Pro_Product_Form::title( $object_id, $product_title ); ?>
		  <script>
			jQuery(function($){
				$("#_auction_date_of_birth").datepicker({ changeYear: true, dateFormat: 'yy-mm-dd'});
				$('.hide_if_variable').remove();
				$('ul.tabs-nav').remove();
				
			});
		 </script>
		<?php
		
		// <!-- KC-new field animal id tag -->
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_simple_auctions_reserved_price', array(
	            'post_id'               => $object_id,
	            'id'                    => '_auction_animal_id_tag',
	            'label'                 => __( 'Animal Id Tag', 'wc_simple_auctions' ),
	            'data_type'             => 'text',
	            'wrapper_start'         => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">',
	            'wrapper_end'           =>  '</div>',
				'custom_attributes' => array(
					'required' => "",
						),
					) )
				);
				
				$animal_sex = get_post_meta($object_id,'_auction_animal_sex',true);
				$checked = '';$malechecked ='';
				if(!empty($animal_sex)){
					if($animal_sex =='male'){
						$mchecked = "checked='checked'";
					}else if($animal_sex =='female'){
						$fchecked = "checked='checked'";
					}else{
						$checked = "";
					}
				}else{
					$malechecked = "checked='checked'";
				}
					
					
				
		echo '<div class="all-50 small-100">
		<div class="control-group">
				<label for="_auction_animal_sex" class="">Sex* </label>
				<div class="control">
					<div style="display:inline-block;padding: 5px 0 5px 4px;">
					<input style="width:auto;" type="radio" id="_auction_animal_sex_male" name="_auction_animal_sex" value="male" '.$mchecked.' '.$malechecked.' >
					<label for="_auction_animal_sex_male" class="sexlabel">Male</label>
					</div>
					<div style="display:inline-block;padding: 5px 0 5px 4px;">
					<input style="width:auto;margin-top:10px;" type="radio" id="_auction_animal_sex_female" name="_auction_animal_sex" value="female" '.$fchecked.' >
					<lable for="_auction_animal_sex_female" class="sexlabel">Female</label>
					</div>
				</div>
		</div></div></div>';	
		
		
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_simple_auctions_date_of_birth', array( 
			'post_id'		=> $object_id, 
			'id' 			=> '_auction_date_of_birth', 
			'label' 		=> __( 'Date Of Birth', 'wcvendors-pro-simple-auctions' ), 
			'class'			=> 'wcv-datepicker', 
			'placeholder'	=> __( '', 'placeholder', 'wcvendors-pro-simple-auctions' ). ' YYYY-MM-DD',  
			'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100 small-100 ">',
			'wrapper_end' 	=> '</div></div>', 
			'custom_attributes' => array(
 		        'autocomplete' => 'off',
				'required' => "",
				'maxlenth' 	=> '10', 
				'pattern' 	=> '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])'
				),
			) )
		);				
		?>
		<!-- KC-new field end -->
		
		
		<!-- Product Description -->
	<div style="opacity:0;position:absolute;">
		<?php WCVendors_Pro_Product_Form::description( $object_id, $product_description );  ?>
	</div>	
		<!-- Product Short Description -->
		<?php WCVendors_Pro_Product_Form::short_description( $object_id, $product_short_description );  ?>
		<!-- Product Categories -->
	    <?php WCVendors_Pro_Product_Form::categories( $object_id, true ); ?>
	    <!-- Product Tags -->
	    <?php WCVendors_Pro_Product_Form::tags( $object_id, true ); ?>
	</div>

	<div class="all-100"> 
    	<!-- Media uploader -->
		<div class="wcv-product-media">
			<?php do_action( 'wcv_before_media', $object_id ); ?>
				<?php WCVendors_Pro_Form_helper::product_media_uploader( $object_id ); ?>
			<?php do_action( 'wcv_after_media', $object_id ); ?>

		</div>
	</div>
	
		<!-- KC-new field youtube -->
	<div class="all-100">
		<?php
		
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_simple_auctions_animal_video', array( 
			'post_id'		=> $object_id, 
			'id' 			=> '_auction_animal_video', 
			'type' 				=> 'url', 
			'label' 		=> __( 'Add Your listing Youtube/Vimeo Url Here ', 'wcvendors-pro-simple-auctions' ),
			'custom_attributes' => array(
				'pattern' 	=> 'https?://.+'
				),
			) )
		);
		
		?>	
	</div>

	<!-- <hr /> -->
	
	<div class="all-100">
  <?php do_action( 'wcv_before_product_type', $object_id ); ?>
		<!-- Product Type -->
		<div class="wcv-product-type" style="display:none;"> 
			<?php WCVendors_Pro_Product_Form::product_type( $object_id ); ?>
		</div>
	</div>

	<div class="all-100">
		<div class="wcv-tabs top" data-prevent-url-change="true">

			<?php WCVendors_Pro_Product_Form::product_meta_tabs( ); ?>

			<?php do_action( 'wcv_before_general_tab', $object_id ); ?>
	
			<!-- General Product Options -->
			<div class="wcv-product-general tabs-content" id="general">
			
				<div class="hide_if_grouped">
					<!-- SKU  -->
					<?php WCVendors_Pro_Product_Form::sku( $object_id ); ?>
					<!-- Private listing  -->
					<?php WCVendors_Pro_Product_Form::private_listing( $object_id ); ?>
				</div>


				<div class="options_group show_if_external">
					<?php WCVendors_Pro_Product_Form::external_url( $object_id ); ?>
					<?php WCVendors_Pro_Product_Form::button_text( $object_id ); ?>
				</div>

				<div class="show_if_simple show_if_external">
					<!-- Price and Sale Price -->
					<?php WCVendors_Pro_Product_Form::prices( $object_id ); ?>
				</div>

				<div class="show_if_simple show_if_external show_if_variable"> 
					<!-- Tax -->
					<?php WCVendors_Pro_Product_Form::tax( $object_id ); ?>
				</div>

				<div class="show_if_downloadable" id="files_download">
					<!-- Downloadable files -->
					<?php WCVendors_Pro_Product_Form::download_files( $object_id ); ?>
					<!-- Download Limit -->
					<?php WCVendors_Pro_Product_Form::download_limit( $object_id ); ?>
					<!-- Download Expiry -->
					<?php WCVendors_Pro_Product_Form::download_expiry( $object_id ); ?>
					<!-- Download Type -->
					<?php WCVendors_Pro_Product_Form::download_type( $object_id ); ?>
				</div>

				<?php do_action( 'wcv_product_options_general_product_data', $object_id ); ?>

			</div>

			<?php do_action( 'wcv_after_general_tab', $object_id ); ?>

			<?php do_action( 'wcv_before_inventory_tab', $object_id ); ?>

			<!-- Inventory -->
			<div class="wcv-product-inventory inventory_product_data tabs-content" id="inventory">
				
				<?php WCVendors_Pro_Product_Form::manage_stock( $object_id ); ?>
				
				<?php do_action( 'wcv_product_options_stock' ); ?>
				
				<div class="stock_fields show_if_simple show_if_variable">
					<?php WCVendors_Pro_Product_Form::stock_qty( $object_id ); ?>
					<?php WCVendors_Pro_Product_Form::backorders( $object_id ); ?>
				</div>

				<?php WCVendors_Pro_Product_Form::stock_status( $object_id ); ?>
				<div class="options_group show_if_simple show_if_variable">
					<?php WCVendors_Pro_Product_Form::sold_individually( $object_id ); ?>
				</div>

				<?php do_action( 'wcv_product_options_sold_individually', $object_id ); ?>

				<?php do_action( 'wcv_product_options_inventory_product_data', $object_id ); ?>

			</div>

			<?php do_action( 'wcv_after_inventory_tab', $object_id ); ?>

			<?php do_action( 'wcv_before_shipping_tab', $object_id ); ?>

			<!-- Shipping  -->
			<div class="wcv-product-shipping shipping_product_data tabs-content" id="shipping">

				<div class="hide_if_grouped hide_if_external">

					<!-- Shipping rates  -->
					<?php WCVendors_Pro_Product_Form::shipping_rates( $object_id ); ?>	
					<!-- weight  -->
					<?php WCVendors_Pro_Product_Form::weight( $object_id ); ?>
					<!-- Dimensions -->
					<?php WCVendors_Pro_Product_Form::dimensions( $object_id ); ?>
					<?php do_action( 'wcv_product_options_dimensions' ); ?>
					<!-- shipping class -->
					<?php WCVendors_Pro_Product_Form::shipping_class( $object_id ); ?>
					
					<?php do_action( 'wcv_product_options_shipping', $object_id ); ?>

					<?php do_action( 'wcv_product_options_shipping_data_panel', $object_id ); ?>
				</div>
			
			</div>

			<?php do_action( 'wcv_after_shipping_tab', $object_id ); ?>

			<?php do_action( 'wcv_before_linked_tab', $object_id ); ?>

			<!-- Upsells and grouping -->
			<div class="wcv-product-upsells tabs-content" id="linked_product"> 

				<?php WCVendors_Pro_Product_Form::up_sells( $object_id ); ?>
				
				<?php WCVendors_Pro_Product_Form::crosssells( $object_id ); ?>

				<div class="hide_if_grouped hide_if_external">
					<?php WCVendors_Pro_Product_Form::grouped_products( $object_id, $product ); ?>
				</div>

				<?php do_action( 'wcv_product_options_upsells_product_data' ); ?>
			</div>

			<?php do_action( 'wcv_after_linked_tab', $object_id ); ?>

			<!-- Attributes -->

			<?php do_action( 'wcv_before_attributes_tab', $object_id ); ?>

			<div class="wcv_product_attributes tabs-content" id="attributes"> 

				<?php WCVendors_Pro_Product_Form::product_attributes( $object_id ); ?>

				<?php do_action( 'wcv_product_options_attributes_product_data' ); ?>

			</div>
			
			<?php do_action( 'wcv_after_attributes_tab', $object_id ); ?>

			<!-- Variations -->

			<?php do_action( 'wcv_before_variations_tab', $object_id ); ?>

			<div class="wcv_product_variations tabs-content" id="variations" style="display:none;"> 

				<?php WCVendors_Pro_Product_Form::product_variations( $object_id ); ?>

				<?php do_action( 'wcv_product_options_variations_product_data' ); ?>

			</div>

			<?php do_action( 'wcv_after_variations_tab', $object_id ); ?>

			<?php WCVendors_Pro_Product_Form::form_data( $object_id, $post_status ); ?>
			<?php WCVendors_Pro_Product_Form::save_button( $title ); ?>
			<?php WCVendors_Pro_Product_Form::draft_button( __('Save Draft','wcvendors-pro') ); ?>

			</div>
		</div>
</form>
