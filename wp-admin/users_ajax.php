<?php

$path =dirname(dirname(__FILE__))."/wp-load.php";
 
include($path);

if(isset($_REQUEST['checkboxval'])){
	
	 $checkboxval_n = $_REQUEST['checkboxval'];
	 $hidden_id_val_n = $_REQUEST['hidden_id_val'];
	
	
	global $wpdb;
	
	 $usermeta = $wpdb->prefix . usermeta;
	
	
	$updae_sql =$wpdb->query("update $usermeta set `meta_value`='$checkboxval_n' where `user_id`='$hidden_id_val_n' and `meta_key`='ja_disable_user'");
	
	echo "Successfully updated the user";
	
}

?>