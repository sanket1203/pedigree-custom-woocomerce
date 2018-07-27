<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DTWPB_Admin{
	
	public function __construct(){
		add_action ('admin_init', array(&$this,'init'));
		// Product, checkout page meta data
		add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
		add_action('save_post', array(&$this, 'save_product_meta_data'), 1, 2 );
		
		// Product category custom single product page
		add_action('product_cat_add_form_fields', array(&$this, 'add_category_fields'));
		add_action('product_cat_edit_form_fields', array(&$this, 'edit_category_fields'), 10, 2);
		add_action('created_term', array(&$this, 'save_category_fields'), 10, 3);
		add_action('edit_term', array(&$this, 'save_category_fields'), 10, 3);
		
		// Add My Account Page Before Login
		add_filter('woocommerce_account_settings', array(&$this, 'dtwpb_woocommerce_account_settings'), 10, 1);
	}
	
	public function init(){
		wp_enqueue_style('dtwpb-admin', DT_WOO_PAGE_BUILDER_URL . 'assets/css/admin.css');
		wp_register_script( 'dtwpb-admin',DT_WOO_PAGE_BUILDER_URL. 'assets/js/admin.js', array('jquery'),DT_WOO_PAGE_BUILDER_VERSION,false);
		wp_enqueue_script( 'dtwpb-admin' );
		wp_enqueue_style('jquery-chosen', DT_WOO_PAGE_BUILDER_URL. 'assets/css/chosen/chosen.css');
		wp_enqueue_script( 'jquery-chosen', DT_WOO_PAGE_BUILDER_URL . '/assets/js/chosen/chosen.jquery.js', array( 'jquery' ), '1.1.0', true );

		// Add Checkout Page custom option
		add_filter('woocommerce_payment_gateways_settings', array(&$this, 'dtwpb_woocommerce_checkout_settings'), 10, 1);
	}
	
	public function add_meta_boxes(){
		global $post;
		
		add_meta_box('dtwpb-single-product-meta-box', __( 'Product Custom Page', 'dt_woocommerce_page_builder'), array(&$this, 'add_product_meta_box'), 'product', 'side');
		
	}
	
	public function add_product_meta_box(){
		
		$product_id = get_the_ID();
		$page_id	= get_post_meta($product_id, 'dtwpb_single_product_page', true);
		$args = array(
			'post_status'	=> 'publish,private',
			'name'			=> 'dtwpb_single_product_page',
			'show_option_none' => esc_html__('None', 'dt_woocommerce_page_builder'),
			'echo'			=> false,
			'selected'		=> absint($page_id),
			'default'		=> '',
		);
		echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;', 'dt_woocommerce_page_builder') .  "' class='' id=", wp_dropdown_pages( $args ) );

	}
	
	
	public function save_product_meta_data($post_id,$post){
		if( empty($post_id) || empty($post) )
			return;
		
		// Dont' save meta boxes for revisions or autosaves
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}
		
		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events
		if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
			return;
		}
		
		// Check user has permission to edit
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		
		if(!empty($_POST['dtwpb_single_product_page'])){
			update_post_meta( $post_id, 'dtwpb_single_product_page', absint($_POST['dtwpb_single_product_page']) );
		}else{
			delete_post_meta( $post_id, 'dtwpb_single_product_page');
		}
	}
	
	public function add_category_fields(){
		?>
		<div class="form-field">
			<label for="dtwpb_cat_product_page"><?php _e( 'Product Custom Page', 'dt_woocommerce_page_builder' ); ?></label>
			<?php 
			$args = array(
					'post_status' => 'publish,private',
					'name'=>'dtwpb_cat_product_page',
					'show_option_none'=>__( 'None','dt_woocommerce_page_builder'),
					'echo'=>false,
			);
			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;','dt_woocommerce_page_builder') .  "' class='enhanced chosen_select_nostd' id=", wp_dropdown_pages( $args ) );
			?>
		</div>
		<div class="form-field">
			<label for="dtwpb_product_cat_custom_page"><?php esc_html_e( 'Category Custom Page', 'dt_woocommerce_page_builder' ); ?></label>
			<?php 
			$args = array(
					'post_status' => 'publish,private',
					'name'=>'dtwpb_product_cat_custom_page',
					'show_option_none'=>__( 'None','dt_woocommerce_page_builder'),
					'echo'=>false,
			);
			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;','dt_woocommerce_page_builder') .  "' class='enhanced chosen_select_nostd' id=", wp_dropdown_pages( $args ) );
			?>
		</div>
		<?php
	}
	
	public function edit_category_fields( $term, $taxonomy ) {
		$dtwpb_cat_product_page = get_woocommerce_term_meta( $term->term_id, 'dtwpb_cat_product_page', true );
		$dtwpb_product_cat_custom_page = get_woocommerce_term_meta( $term->term_id, 'dtwpb_product_cat_custom_page', true );
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Product Custom Page', 'dt_woocommerce_page_builder' ); ?></label></th>
			<td>
				<?php 
				$args = array(
						'post_status' => 'publish,private',
						'name'=>'dtwpb_cat_product_page',
						'show_option_none'=>__( 'None','dt_woocommerce_page_builder'),
						'echo'=>false,
						'selected'=>absint($dtwpb_cat_product_page)
				);
				echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;','dt_woocommerce_page_builder') .  "' class='enhanced chosen_select_nostd' id=", wp_dropdown_pages( $args ) );
				
				?>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Category Custom Page', 'dt_woocommerce_page_builder' ); ?></label></th>
			<td>
				<?php 
				$args = array(
						'post_status' => 'publish,private',
						'name'=>'dtwpb_product_cat_custom_page',
						'show_option_none'=>__( 'None','dt_woocommerce_page_builder'),
						'echo'=>false,
						'selected'=>absint($dtwpb_product_cat_custom_page)
				);
				echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;','dt_woocommerce_page_builder') .  "' class='enhanced chosen_select_nostd' id=", wp_dropdown_pages( $args ) );
				
				?>
			</td>
		</tr>
		<?php
	}
	
	public function save_category_fields( $term_id, $tt_id, $taxonomy ) {
	
		if(!empty($_POST['dtwpb_cat_product_page'])){
			update_woocommerce_term_meta( $term_id, 'dtwpb_cat_product_page', absint( $_POST['dtwpb_cat_product_page'] ) );
		}else{
			delete_woocommerce_term_meta($term_id,  'dtwpb_cat_product_page');
		}
		
		if(!empty($_POST['dtwpb_product_cat_custom_page'])){
			update_woocommerce_term_meta( $term_id, 'dtwpb_product_cat_custom_page', absint( $_POST['dtwpb_product_cat_custom_page'] ) );
		}else{
			delete_woocommerce_term_meta($term_id,  'dtwpb_product_cat_custom_page');
		}
	}

	public function dtwpb_woocommerce_checkout_settings($settings){
		$updated_settings = array();

		// at the top of the My Account section
		/*$checkout_custem_template =array(
				'title'    => esc_html__( 'Checkout Page Custom Template?', 'dt_woocommerce_page_builder' ),
				'desc'     => esc_html__( 'Use the shortcodes of WooCommerce Page Builder to custom the Checkout page. Note: Remove the [woocommerce_checkout] shortcode default when use the custom shortcodes.', 'dt_woocommerce_page_builder' ),
				'id'       => 'dtwpb_woocommerce_checkout_custem_template',
				'default'  => 'def',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' =>  true,
				'options'  => array(
					'def'        => esc_html__( 'Default (use shortcode [woocommerce_checkout])', 'dt_woocommerce_page_builder' ),
					'used_checkout_custem_template'   => esc_html__( 'Use WooCommerce Page Builder Checkout shortcodes', 'dt_woocommerce_page_builder' )
				)
		);*/
		$checkout_custem_template = array(
				'title'    => esc_html__( 'Checkout Page Custom Template?', 'dt_woocommerce_page_builder' ),
				'desc'          => esc_html__( 'Use WooCommerce Page Builder Checkout shortcodes?', 'dt_woocommerce_page_builder' ),
				'id'            => 'dtwpb_woocommerce_checkout_custem_template',
				'default'       => 'no',
				'type'          => 'checkbox',
				'desc_tip'      =>  esc_html__( 'Default (use shortcode [woocommerce_checkout]). Note: Remove the [woocommerce_checkout] shortcode standard default when use the custom shortcodes of WooCommerce Page Builder.', 'dt_woocommerce_page_builder' ),
		);
		
		foreach ($settings as $section){
			if ( isset( $section['id'] ) && 'woocommerce_checkout_page_id' == $section['id'] && isset( $section['type'] ) && 'single_select_page' == $section['type'] ) {
				
				$updated_settings[] = $section;
				$updated_settings[] = $checkout_custem_template;
			}else{
				$updated_settings[] = $section;
			}
		}

		return $updated_settings;
	}
	
	public function dtwpb_woocommerce_account_settings($settings){
		$updated_settings = array();
		
		// at the top of the My Account section
		$myaccount_before_login = array(
				'title'    => esc_html__( 'Before Login Page', 'dt_woocommerce_page_builder' ),
				'desc'     => esc_html__( 'Custom page before user login. Go to build a custom MyAccount Before login page, use the elements in the "DT Woo MyAccount Before Login". You can also add the steps/description how to create an account for this custom page.', 'dt_woocommerce_page_builder' ),
				'id'       => 'dtwpb_woocommerce_myaccount_before_login_page_id',
				'type'     => 'single_select_page',
				'default'  => '',
				'class'    => 'wc-enhanced-select',
				'css'      => 'min-width:300px;',
				'desc_tip' => true,
			);
		
		foreach ($settings as $section){
			if ( isset( $section['id'] ) && 'account_page_options' == $section['id'] && isset( $section['type'] ) && 'title' == $section['type'] ) {
				$updated_settings[] = $section;
				$updated_settings[] = $myaccount_before_login;
			}else{
				$updated_settings[] = $section;
			}
		}

		return $updated_settings;
	}
	
	
	// End Class
}
new DTWPB_Admin();