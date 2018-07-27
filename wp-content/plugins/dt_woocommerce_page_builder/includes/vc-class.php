<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class DTWPB_VC_CLASS{
	
	public function __construct(){
		$this->init();
	}
	
	public function init(){
		
		if( defined('WPB_VC_VERSION') && function_exists('vc_add_param') ){
			
			require_once DT_WOO_PAGE_BUILDER_DIR . '/includes/vc-map/vc-map.php';
			
		}
	}
//
}

new DTWPB_VC_CLASS();

// require shortcodes
if ( class_exists( 'WooCommerce' ) )
require_once DT_WOO_PAGE_BUILDER_DIR . '/includes/vc-shortcodes/vc-shortcodes.php';
