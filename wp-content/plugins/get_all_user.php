<?php 
include '../../wp-load.php'; 
global $wpdb;  

function pre($dataArr){
    echo '<pre>';
    print_r($dataArr);
    echo '</pre>';
}
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_URL,'https://www.pedigreesalesonline.co.uk/wp-content/plugins/get_all_user.php'); 
$result=curl_exec($ch); 
curl_close($ch); 
$UserArr = json_decode($result, true);
foreach($UserArr as $key => $UserItemArr)
{ 
     $userdata = array(  
                'user_login'  =>  $UserItemArr['user_login'],
                'user_pass'    => $UserItemArr['user_pass'], 
                'user_nicename'    => $UserItemArr['user_nicename Code'],  
                'user_email'   =>   $UserItemArr['user_email'],    
                'user_url'   =>   $UserItemArr['user_url'],    
                'user_registered'  => $UserItemArr['user_registered'],    
                'user_activation_key'   => $UserItemArr['user_activation_key'], 
                'user_status'   => $UserItemArr['user_status'], 
                'display_name'   => $UserItemArr['display_name'], 
                'driving_license'   => $UserItemArr['driving_license'], 
                'utility_bill'   => $UserItemArr['utility_bill']
								);  
if(!username_exists( $UserItemArr['user_login']) && !email_exists( $UserItemArr['user_email'] )){
	$user_id = wp_insert_user( $userdata ); 
	if(!empty($user_id)){
update_user_meta($user_id, 'nickname',$UserItemArr['userMeta']['nickname'][0]);
update_user_meta($user_id, 'first_name',$UserItemArr['userMeta']['first_name'][0]);
update_user_meta($user_id, 'last_name',$UserItemArr['userMeta']['last_name'][0]);
update_user_meta($user_id, 'description',$UserItemArr['userMeta']['description'][0]);
update_user_meta($user_id, 'rich_editing',$UserItemArr['userMeta']['rich_editing'][0]);
update_user_meta($user_id, 'comment_shortcuts',$UserItemArr['userMeta']['comment_shortcuts'][0]);
update_user_meta($user_id, 'admin_color',$UserItemArr['userMeta']['admin_color'][0]);
update_user_meta($user_id, 'real_name',$UserItemArr['userMeta']['real_name'][0]);
update_user_meta($user_id, 'surname',$UserItemArr['userMeta']['surname'][0]);
update_user_meta($user_id, 'address',$UserItemArr['userMeta']['address'][0]);
update_user_meta($user_id, 'contact',$UserItemArr['userMeta']['contact'][0]);
update_user_meta($user_id, 'mobile_number',$UserItemArr['userMeta']['mobile_number'][0]);
update_user_meta($user_id, 'landline',$UserItemArr['userMeta']['landline'][0]);
update_user_meta($user_id, 'tsvu_capabilities',$UserItemArr['userMeta']['9ofkk_capabilities'][0]);
update_user_meta($user_id, 'tsvu_user_level',$UserItemArr['userMeta']['9ofkk_user_level'][0]);    
}}}
?>



