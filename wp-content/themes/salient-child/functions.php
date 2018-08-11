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

/*KC-new fields general tab */
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );
function woo_add_custom_general_fields() {

  global $woocommerce, $post;
  echo '
    <script>
	jQuery(function($){
            $("#_auction_date_of_birth").datepicker({ changeYear: true,dateFormat:"dd-mm-yy"});
        
			 //$("#_auction_dates_from").datepicker({ changeYear: true});
			  //$("#_auction_dates_to").datepicker({ changeYear: true});
        });
    </script>';
  echo '<div class="options_group">';	  
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_animal_id_tag', 
				'label'       => __( 'Animal id Tag', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the custom value here.', 'woocommerce' ) 
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_date_of_birth', 
				'label'       => __( 'Date of Birth', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => 'datetimepicker',
				'custom_attributes' => array('autocomplete' =>'off'),
				'description' => __( 'Select the date of birth.', 'woocommerce' ) 
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_animal_video', 
				'label'       => __( 'Add Your listing Youtube/Vimeo Url Here', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the animal tag id.', 'woocommerce' ) 
			)
		);
		woocommerce_wp_radio( 
			array( 
				'id'          => '_auction_animal_sex', 
				'label'       => __( 'Sex', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'options' =>  array( 'male' => __( 'Male', 'woocommerce' ),
							'female' => __( 'Female', 'woocommerce' ),
							 ),
				'description' => __( 'Select Gender.', 'woocommerce' ) 
			)
		);
		  echo '</div>';
}

add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );
function woo_add_custom_general_fields_save( $post_id ){
	$_auction_animal_id_tag = $_POST['_auction_animal_id_tag'];
	if( !empty( $_auction_animal_id_tag ) )
		update_post_meta( $post_id, '_auction_animal_id_tag', esc_attr( $_auction_animal_id_tag ) );
	
	$_auction_date_of_birth = $_POST['_auction_date_of_birth'];
	if( !empty( $_auction_date_of_birth ) )
		update_post_meta( $post_id, '_auction_date_of_birth', esc_attr( $_auction_date_of_birth ) );
	
	$_auction_animal_video = $_POST['_auction_animal_video'];
	if( !empty( $_auction_animal_video ) )
		update_post_meta( $post_id, '_auction_animal_video', esc_attr( $_auction_animal_video ) );
	
	$_auction_animal_sex = $_POST['_auction_animal_sex'];
	if( !empty( $_auction_animal_sex ) )
		update_post_meta( $post_id, '_auction_animal_sex', esc_attr( $_auction_animal_sex ) );
	
	$_auction_birth_weight = $_POST['_auction_birth_weight'];
	if( !empty( $_auction_birth_weight ) )
		update_post_meta( $post_id, '_auction_birth_weight', esc_attr( $_auction_birth_weight ) );
	
	$_auction_weaning_weight = $_POST['_auction_weaning_weight'];
	if( !empty( $_auction_weaning_weight ) )
		update_post_meta( $post_id, '_auction_weaning_weight', esc_attr( $_auction_weaning_weight ) );
	
	$_auction_yearling_weight = $_POST['_auction_yearling_weight'];
	if( !empty( $_auction_yearling_weight ) )
		update_post_meta( $post_id, '_auction_yearling_weight', esc_attr( $_auction_yearling_weight ) );
	
	$_auction_expecated_sale_weight = $_POST['_auction_expecated_sale_weight'];
	if( !empty( $_auction_expecated_sale_weight ) )
		update_post_meta( $post_id, '_auction_expecated_sale_weight', esc_attr( $_auction_expecated_sale_weight ) );
	
	$_auction_average_daily_gain_weight = $_POST['_auction_average_daily_gain_weight'];
	if( !empty( $_auction_expecated_sale_weight ) )
		update_post_meta( $post_id, '_auction_average_daily_gain_weight', esc_attr( $_auction_average_daily_gain_weight ) );

	$_auction_sire = $_POST['_auction_sire'];
	if( !empty( $_auction_sire ) )
		update_post_meta( $post_id, '_auction_sire', esc_attr( $_auction_sire ) );
	$_auction_second_genration_one = $_POST['_auction_second_genration_one'];
	if( !empty( $_auction_second_genration_one ) )
		update_post_meta( $post_id, '_auction_second_genration_one', esc_attr( $_auction_second_genration_one ) );
	
	$_auction_second_genration_two = $_POST['_auction_second_genration_two'];
	if( !empty( $_auction_second_genration_two ) )
		update_post_meta( $post_id, '_auction_second_genration_two', esc_attr( $_auction_second_genration_two ) );
	
	$_auction_second_genration_two = $_POST['_auction_second_genration_two'];
	if( !empty( $_auction_second_genration_two ) )
		update_post_meta( $post_id, '_auction_second_genration_two', esc_attr( $_auction_second_genration_two ) );
	
	$_auction_third_genration_one = $_POST['_auction_third_genration_one'];
	if( !empty( $_auction_third_genration_one ) )
		update_post_meta( $post_id, '_auction_third_genration_one', esc_attr( $_auction_third_genration_one ) );
	
	$_auction_third_genration_two = $_POST['_auction_third_genration_two'];
	if( !empty( $_auction_third_genration_two ) )
		update_post_meta( $post_id, '_auction_third_genration_two', esc_attr( $_auction_third_genration_two ) );
	
	$_auction_third_genration_three = $_POST['_auction_third_genration_three'];
	if( !empty( $_auction_third_genration_three ) )
		update_post_meta( $post_id, '_auction_third_genration_three', esc_attr( $_auction_third_genration_three ) );
	
	$_auction_third_genration_four = $_POST['_auction_third_genration_four'];
	if( !empty( $_auction_third_genration_four ) )
		update_post_meta( $post_id, '_auction_third_genration_four', esc_attr( $_auction_third_genration_four ) );
	
	$_auction_dam = $_POST['_auction_dam'];
	if( !empty( $_auction_dam ) )
		update_post_meta( $post_id, '_auction_dam', esc_attr( $_auction_dam ) );
	
	$_auction_dam_second_genration_one = $_POST['_auction_dam_second_genration_one'];
	if( !empty( $_auction_dam_second_genration_one ) )
		update_post_meta( $post_id, '_auction_dam_second_genration_one', esc_attr( $_auction_dam_second_genration_one ) );
	
	$_auction_second_genration_two = $_POST['_auction_second_genration_two'];
	if( !empty( $_auction_second_genration_two ) )
		update_post_meta( $post_id, '_auction_second_genration_two', esc_attr( $_auction_second_genration_two ) );
	
	$_auction_dam_third_genration_one = $_POST['_auction_dam_third_genration_one'];
	if( !empty( $_auction_dam_third_genration_one ) )
		update_post_meta( $post_id, '_auction_dam_third_genration_one', esc_attr( $_auction_dam_third_genration_one ) );
	
	$_auction_dam_third_genration_two = $_POST['_auction_dam_third_genration_two'];
	if( !empty( $_auction_dam_third_genration_two ) )
		update_post_meta( $post_id, '_auction_dam_third_genration_two', esc_attr( $_auction_dam_third_genration_two ) );
	
	$_auction_dam_third_genration_three = $_POST['_auction_dam_third_genration_three'];
	if( !empty( $_auction_dam_third_genration_three ) )
		update_post_meta( $post_id, '_auction_dam_third_genration_three', esc_attr( $_auction_dam_third_genration_three ) );

	$_auction_dam_third_genration_four = $_POST['_auction_dam_third_genration_four'];
	if( !empty( $_auction_dam_third_genration_four ) )
		update_post_meta( $post_id, '_auction_dam_third_genration_four', esc_attr( $_auction_dam_third_genration_four ) );	
	

}
/*KC-new Aution tab */
add_action( 'woocommerce_product_options_auction', 'woo_add_custom_autions_fields' );
function woo_add_custom_autions_fields() {

  global $woocommerce, $post;		
	echo '<div class="options_group">';	
		echo "<h3 style='padding-left:10px;'>ICBF* Verified Weight</h3>";
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_birth_weight', 
				'label'       => __( 'Birth Weight', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the Birth Weight.', 'woocommerce' ) 
			)
		);
		
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_weaning_weight', 
				'label'       => __( 'Weaning Weight', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the Weaning Weight.', 'woocommerce' ) 
			)
		);
		
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_yearling_weight', 
				'label'       => __( 'Yearling Weight', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the Yearling Weight.', 'woocommerce' ) 
			)
		);
		echo "<h3 style='padding-left:10px;'>Weight Predictor</h3>";
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_expecated_sale_weight', 
				'label'       => __( 'Expected Sale Weight', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the Expected Sale Weight.', 'woocommerce' ) 
			)
		);
		
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_average_daily_gain_weight', 
				'label'       => __( 'Average Daily Gain', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the Average Daily Gain.', 'woocommerce' ) 
			)
		);
		echo "<h3 style='padding-left:10px;'>Bloodline</h3>";
		echo "<h4 style='padding-left:10px;'>Sire</h4>";
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_sire', 
				'label'       => __( 'Sire', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		
		// Sire image 
		$sire_image_id = get_post_meta(get_the_ID(),'_auction_sire_image',true);
		WCVendors_Pro_Form_Helper::file_uploader( apply_filters( 'wcv_simple_auctions_sire_image', array(
	            'value'             => $sire_image_id,
				'size'				=> 'thumbnail',
				'class'				=> 'animal_image',
	            'image_meta_key'    => '_auction_sire_image',
				'save_button'		=> __('Add image', 'wcvendors-pro' ), 
				'header_text'		=> __('File uploader', 'wcvendors-pro' ), 
				'add_text' 			=> __('Add Sire image', 'wcvendors-pro' ), 
				'remove_text'		=> __('Remove image', 'wcvendors-pro' ), 	            
				'window_title'		=> __('Select an Image', 'wcvendors-pro' ), 				
	            'wrapper_start'         => '<div style="margin-left:10px;">',
	            'wrapper_end'           =>  '</div>'
	            ) )
	    );
		
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_second_genration_one', 
				'label'       => __( '2nd Generation text one', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_second_genration_two', 
				'label'       => __( '2nd Generation text two', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_third_genration_one', 
				'label'       => __( '3rd Generation text one', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_third_genration_two', 
				'label'       => __( '3rd Generation text two', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_third_genration_three', 
				'label'       => __( '3rd Generation text three', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_third_genration_four', 
				'label'       => __( '3rd Generation text four', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		
		echo "<h4 style='padding-left:10px;'>Dam</h4>";
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_dam', 
				'label'       => __( 'Dam', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		// Dam image 
		$dam_image_id = get_post_meta(get_the_ID(),'_auction_dam_image',true);
		WCVendors_Pro_Form_Helper::file_uploader( apply_filters( 'wcv_simple_auctions_dam_image', array(
	            'value'             => $dam_image_id,
				'size'		        => 'thumbnail',
				'class'				=> 'animal_image',
	            'image_meta_key'        => '_auction_dam_image',
				'save_button'		=> __('Add image', 'wcvendors-pro' ), 
				'header_text'		=> __('File uploader', 'wcvendors-pro' ), 
				'add_text' 			=> __('Add Dam image', 'wcvendors-pro' ), 
				'remove_text'		=> __('Remove image', 'wcvendors-pro' ), 	            
				'window_title'		=> __('Select an Image', 'wcvendors-pro' ), 				
	            'wrapper_start'         => '<div style="margin-left:10px;">',
	            'wrapper_end'           =>  '</div>'
	            ) )
	    );
		
		
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_dam_second_genration_one', 
				'label'       => __( '2nd Generation text one', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_second_genration_two', 
				'label'       => __( '2nd Generation text two', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
	
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_dam_third_genration_one', 
				'label'       => __( '3rd Generation text one', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_dam_third_genration_two', 
				'label'       => __( '3rd Generation text two', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_dam_third_genration_three', 
				'label'       => __( '3rd Generation text three', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_auction_dam_third_genration_four', 
				'label'       => __( '3rd Generation text four', 'woocommerce' ), 
				'placeholder' => '',
				'desc_tip'    => 'true',
				'class'	 	  => '',
				'description' => __( 'Enter the sire text.', 'woocommerce' ) 
			)
		);
		
  echo '</div>';
?>
<script type="text/javascript">
jQuery(document).ready(function ($) {
  $('.wcv-img-id').each( function () {
	    var id = this.id; 
		$('.wcv-file-uploader-add' + id ).on( 'click', function(e) { 
			e.preventDefault(); 
			file_uploader( id ); 
			return false; 
		}); 

		$('.wcv-file-uploader-delete' + id ).on('click', function(e) { 
			e.preventDefault(); 
			// reset the data so that it can be removed and saved. 
			$( '.wcv-file-uploader' + id ).html(''); 
			$( 'input[id=' + id + ']').val(''); 
			$('.wcv-file-uploader-delete' + id ).addClass('hidden'); 
			$('.wcv-file-uploader-add' + id ).removeClass('hidden'); 
		});

	});

	function file_uploader( id )
	{
		var media_uploader, json;
		if (undefined !== media_uploader ) { 
			media_uploader.open(); 
			return; 
		}

	    media_uploader = wp.media({
      		title: $( '#' + id ).data('window_title'), 
      		button: {
        		text: $( '#' + id ).data('save_button'), 
      		},
      		multiple: false 
    	});

	    media_uploader.on( 'select' , function(){
	    	json = media_uploader.state().get('selection').first().toJSON(); 

	    	if ( 0 > $.trim( json.url.length ) ) {
		        return;
		    }

		    attachment_image_url = json.sizes.thumbnail ? json.sizes.thumbnail.url : json.url;

		    $( '.wcv-file-uploader' + id )
		    	.append( '<img src="'+ attachment_image_url + '" alt="' + json.caption + '" title="' + json.title +'" style="max-width: 100%;" />' ); 
		    
		    $('#' + id ).val( json.id ); 

		    $('.wcv-file-uploader-add' + id ).addClass('hidden'); 
		    $('.wcv-file-uploader-delete' + id ).removeClass('hidden'); 
	    });
	    media_uploader.open();
	}
});
</script>
<?php	
}
function getYoutubeEmbedUrl($url)
{
   if(strpos($url, 'vimeo.com/') !== false) {
        //it is Vimeo video
        $videoId = explode("vimeo.com/",$url)[1];
        if(strpos($videoId, '&') !== false){
            $videoId = explode("&",$videoId)[0];
        }
        $finalUrl.='https://player.vimeo.com/video/'.$videoId;
    }else if(strpos($url, 'youtube.com/') !== false) {
        //it is Youtube video
        $videoId = explode("v=",$url)[1];
        if(strpos($videoId, '&') !== false){
            $videoId = explode("&",$videoId)[0];
        }
        $finalUrl.='https://www.youtube.com/embed/'.$videoId;
    }else if(strpos($url, 'youtu.be/') !== false){
        //it is Youtube video
        $videoId = explode("youtu.be/",$url)[1];
        if(strpos($videoId, '&') !== false){
            $videoId = explode("&",$videoId)[0];
        }
        $finalUrl.='https://www.youtube.com/embed/'.$videoId;
    }
	return $finalUrl;
}

add_action( 'save_post', 'my_save_post_function', 10, 3 );

function my_save_post_function( $post_ID, $post, $update ) {
  global $wpdb;
  $postname = get_post_type();
  if($postname == 'product'){	    
		$querystr = " UPDATE `".$wpdb->posts."` SET `post_content` = 'test content' WHERE `tsvu_posts`.`ID` = ".$post_ID." ";
		$pageposts = $wpdb->get_results($querystr, OBJECT);

	
	if (isset($_POST['_auction_sire_image'])){		
		update_post_meta( $post_ID, '_auction_sire_image', stripslashes( $_POST['_auction_sire_image'] ) );
	}
	if (isset($_POST['_auction_dam_image'])){
		update_post_meta( $post_ID, '_auction_dam_image', stripslashes( $_POST['_auction_dam_image'] ) );
	}
			
  }
}

?>
