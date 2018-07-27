<?php 
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( !class_exists( 'EO_Registration_Fields_Admin' ) ) { 

	class EO_Registration_Fields_Admin extends Extendons_Registration_Fields {

		public function __construct() {

			add_action( 'wp_loaded', array( $this, 'admin_init' ) );
			
			add_action('wp_ajax_update_sortorder', array($this, 'update_sortorder'));
			add_action('wp_ajax_nopriv_update_sortorder', array($this, 'update_sortorder'));

			add_action('wp_ajax_de_update_sortorder', array($this, 'de_update_sortorder'));
			add_action('wp_ajax_nopriv_de_update_sortorder', array($this, 'de_update_sortorder'));

			add_action('wp_ajax_insert_field', array($this, 'insert_field')); 
			add_action('wp_ajax_nopriv_insert_field', array($this, 'insert_field')); 

			add_action('wp_ajax_fetch_drop_options', array($this, 'fetch_drop_options'));
			add_action("wp_ajax_nopriv_fetch_drop_options", array($this, "fetch_drop_options"));

			add_action('wp_ajax_del_field', array($this, 'del_field'));
			add_action('wp_ajax_nopriv_del_field', array($this, 'del_field'));

			add_action('wp_ajax_save_all_data', array($this, 'save_all_data'));
			add_action('wp_ajax_nopriv_save_all_data', array($this, 'save_all_data'));

			add_action('wp_ajax_de_save_all_data', array($this, 'de_save_all_data'));
			add_action('wp_ajax_nopriv_de_save_all_data', array($this, 'de_save_all_data'));
			
			add_action( 'edit_user_profile', array($this, 'eo_profile_fields' ));
			add_action( 'edit_user_profile_update', array($this, 'eo_update_profile_fields' ));

			
		}


		

		public function admin_init() {
			add_action( 'admin_menu', array( $this, 'create_admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );	
		}

		public function admin_scripts() {	
            
        	wp_enqueue_style( 'eorf-admin-css', plugins_url( '/css/eorf_style.css', __FILE__ ), false );
        	wp_enqueue_script('jquery-ui-tabs');
        	wp_enqueue_script( 'eorf-ui-script', '//code.jquery.com/ui/1.11.4/jquery-ui.js', array('jquery'), false );
        	//wp_enqueue_script( 'eorf-admin-timepicker', plugins_url( '/js/jquery-ui-timepicker-addon.js', __FILE__ ), array('jquery'), false );
        	wp_enqueue_script( 'eorf-admin-jsssssss', plugins_url( '/js/eorf_admin.js', __FILE__ ), array('jquery'), false );
        	
        	wp_enqueue_script('parsley-js', plugins_url( '/js/parsley.min.js', __FILE__ ), false );
			wp_enqueue_style('parsley-css', plugins_url( '/css/parsley.css', __FILE__ ), false );
			
        }

        public function create_admin_menu() {	
			add_menu_page('Registration Fields', __( 'Registration Fields', 'eorf' ), apply_filters( 'eorf_capability', 'manage_options' ), 'eo-registration-fields', array( $this, 'eorf_registration_fields_module' ) ,plugins_url( 'images/ext_icon.png', dirname( __FILE__ ) ), apply_filters( 'eorf_menu_position', 7 ) );


			add_submenu_page( 'eo-registration-fields', __( 'Enable Default Fields', 'eorf' ), __( 'Enable Default Fields', 'eorf' ), 'manage_options', 'eorf_enable_default_fields', array( $this, 'eorf_enable_default_fields' ) );

			add_submenu_page( 'eo-registration-fields', __( 'Settings', 'eorf' ), __( 'Settings', 'eorf' ), 'manage_options', 'eorf_settings', array( $this, 'eorf_mdoule_settings' ) );	
			add_submenu_page( 'eo-registration-fields', __( 'Support', 'eorf' ), __( 'Support', 'eorf' ), 'manage_options', 'eo-registration-fields-support', array( $this, 'eorf_support' ) );

	    }

	    public function eorf_enable_default_fields() {
	    	require  EORF_PLUGIN_DIR . 'admin/view/default_fields.php';
	    }

	    public function eorf_mdoule_settings() {
			require  EORF_PLUGIN_DIR . 'admin/view/settings.php';
		}

		public function eorf_support() {
			require  EORF_PLUGIN_DIR . 'admin/view/support.php';
		}

		

		function eorf_registration_fields_module() {
	    	require_once( EORF_PLUGIN_DIR . 'admin/view/view.php' );
	    }

	    public function get_reg_fields() {
            
             global $wpdb;
             $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_fields." WHERE field_type!='' AND type = %s ORDER BY length(sort_order), sort_order", 'registration'));      
             return $result;
        }


        public function get_reg_de_fields() {
            
             global $wpdb;
             $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_de_fields." WHERE field_type!='' AND type = %s ORDER BY length(sort_order), sort_order", 'default'));      
             return $result;
        }

        function update_sortorder() {
			global $wpdb;
			$fieldids = $_POST['fieldids'];
			$counter = 1;
			foreach ($fieldids as $fieldid) {
				$wpdb->query($wpdb->prepare( 
				            "
			    UPDATE " .$wpdb->eorf_fields." SET sort_order = %d WHERE field_id = %d
			    ",
				    $counter,
				    intval($fieldid)
				));
				
				$counter = $counter + 1;	
			}	

		}

		function de_update_sortorder() {
			global $wpdb;
			$fieldids = $_POST['fieldids'];
			$counter = 1;
			foreach ($fieldids as $fieldid) {
				$wpdb->query($wpdb->prepare( 
				            "
			    UPDATE " .$wpdb->eorf_de_fields." SET sort_order = %d WHERE field_id = %d
			    ",
				    $counter,
				    intval($fieldid)
				));
				
				$counter = $counter + 1;	
			}	

		}

		/*function get_captcha_field($mode) {
			global $wpdb;

             $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_fields." WHERE field_type = %s", $mode));      
             return $result;
		}*/

		function insert_field() {
			global $wpdb;
			$last1 = $wpdb->get_row("SHOW TABLE STATUS LIKE '$wpdb->eorf_fields'");
        	$a = ($last1->Auto_increment);
			if(isset($_POST['fieldtype']) && $_POST['fieldtype']!='') {
				$fieldtype = sanitize_text_field($_POST['fieldtype']);
			} else { $fieldtype = '';}
			if(isset($_POST['type']) && $_POST['type']!='') {
				$type = sanitize_text_field($_POST['type']);
			} else { $type = ''; }
			if(isset($_POST['label']) && $_POST['label']!='') {
				$label = sanitize_text_field($_POST['label']);
			} else { $label = ''; }
			$name = 'registration_field_'.$a;
			if(isset($_POST['mode']) && $_POST['mode']!='') {
				$mode = sanitize_text_field($_POST['mode']);
			} else { $mode = ''; }

			if(isset($_POST['fieldmessage']) && $_POST['fieldmessage']!='') {
				$fieldmessage = sanitize_text_field($_POST['fieldmessage']);
			} else { $fieldmessage = ''; }

			if(isset($_POST['showif']) && $_POST['showif']!='') {
				$showif = sanitize_text_field($_POST['showif']);
			} else { $showif = ''; }

			if(isset($_POST['cfield']) && $_POST['cfield']!='') {
				$cfield = sanitize_text_field($_POST['cfield']);
			} else { $cfield = ''; }

			if(isset($_POST['ccondition']) && $_POST['ccondition']!='') {
				$ccondition = sanitize_text_field($_POST['ccondition']);
			} else { $ccondition = ''; }

			if(isset($_POST['ccondition_value']) && $_POST['ccondition_value']!='') {
				$ccondition_value = sanitize_text_field($_POST['ccondition_value']);
			} else { $ccondition_value = ''; }

			if($fieldtype!='' && $type!='' && $label!='') {

				//$already_google_captcha = $this->get_captcha_field($fieldtype); 
				
				//if(count($already_google_captcha) > 0) {
					//echo json_encode("extalready");
					//exit();
				//} else {
				

					$wpdb->query($wpdb->prepare( 
		            "
		            INSERT INTO $wpdb->eorf_fields
		            (field_name, field_label, field_type, type, field_mode, field_message, showif, cfield, ccondition, ccondition_value)
		            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
		            ",
		            $name,
		            $name, 
		            $fieldtype,
		            $type,
		            $mode,
		            $fieldmessage,
		            $showif,
		            $name,
		            $ccondition,
		            $ccondition_value
		            
		            
		            ) );

		            $last = $wpdb->get_row("SHOW TABLE STATUS LIKE '$wpdb->eorf_fields'");

		        	echo json_encode(($last->Auto_increment)-1);
					exit();
				//}
			}
			
			


		}

		function fetch_drop_options() {
			
			if(isset($_POST['fieldid']) && $_POST['fieldid']!='') {
				$id = intval($_POST['fieldid']);
			} else {
				$id = 0;	
			} 
			global $wpdb;
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_fields." WHERE field_id!=%d", $id));      
            ?>
            <select name="cfield[]" class="cfields">
            <option value=""><?php _e('Select','eorf'); ?></option>
            <?php 
            foreach($results as $res) { ?>
				<option value="<?php echo $res->field_name; ?>"><?php echo $res->field_label; ?></option>
            <?php } ?>
            </select>
			<?php 
			
			exit();

		}

		

		function del_field() {
			$field_id = $_POST['field_id'];
			global $wpdb;
			$wpdb->query( $wpdb->prepare( "DELETE FROM ".$wpdb->eorf_fields . " WHERE field_id = %d", $field_id ) );
			die();
			return true;
		}


		function save_all_data() { 
			global $wpdb;

			if(isset($_POST['option_field_ids']) && $_POST['option_field_ids']!='') {
				$option_field_ids = $_POST['option_field_ids']; 			
			} else {$option_field_ids = array();}
			if(isset($_POST['option_value']) && $_POST['option_value']!='') {
				$option_value = $_POST['option_value'];	
			} else {$option_value = array();}
			if(isset($_POST['option_text']) && $_POST['option_text']!='') {
				$option_text = $_POST['option_text'];			
			} else { $option_text = array(); }


			if(isset($_POST['fieldids']) && $_POST['fieldids']!='') {
				$fieldids = $_POST['fieldids'];			
			} else { $fieldids = array(); }
			if(isset($_POST['fieldlabel']) && $_POST['fieldlabel']!='') {
				$fieldlabel = $_POST['fieldlabel'];			
			} else { $fieldlabel = array(); }
			if(isset($_POST['fieldplaceholder']) && $_POST['fieldplaceholder']!='') {
				$fieldplaceholder = $_POST['fieldplaceholder'];			
			} else { $fieldplaceholder = array(); }
			if(isset($_POST['fieldrequired']) && $_POST['fieldrequired']!='') {
				$fieldrequired = $_POST['fieldrequired'];			
			} else { $fieldrequired = array(); }
			if(isset($_POST['fieldhidden']) && $_POST['fieldhidden']!='') {
				$fieldhidden = $_POST['fieldhidden'];			
			} else { $fieldhidden = array(); }

			if(isset($_POST['fieldreadonly']) && $_POST['fieldreadonly']!='') {
				$fieldreadonly = $_POST['fieldreadonly'];			
			} else { $fieldreadonly = array(); }


			if(isset($_POST['fieldwidth']) && $_POST['fieldwidth']!='') {
				$fieldwidth = $_POST['fieldwidth'];			
			} else { $fieldwidth = array(); }
			if(isset($_POST['fieldmessage']) && $_POST['fieldmessage']!='') {
				$fieldmessage = $_POST['fieldmessage'];			
			} else { $fieldmessage = array(); }


			if(isset($_POST['showif']) && $_POST['showif']!='') {
				$showif = $_POST['showif'];
			} else { $showif = array(); }

			if(isset($_POST['cfield']) && $_POST['cfield']!='') {
				$cfield = $_POST['cfield'];
			} else { $cfield = array(); }

			if(isset($_POST['ccondition']) && $_POST['ccondition']!='') {
				$ccondition = $_POST['ccondition'];
			} else { $ccondition = array(); }

			if(isset($_POST['ccondition_value']) && $_POST['ccondition_value']!='') {
				$ccondition_value = $_POST['ccondition_value'];
			} else { $ccondition_value = array(); }




			$combined_array1 = array_map(function($a, $b, $c) { return $a.'-_-'.$b.'-_-'.$c; }, $option_field_ids, $option_value, $option_text);
			$wpdb->query("DELETE FROM ".$wpdb->eorf_meta );

			if($combined_array1!='') {
				foreach ($combined_array1 as $value) {

					$data = explode('-_-', $value);

					$wpdb->query($wpdb->prepare( 
		            "
		            INSERT INTO $wpdb->eorf_meta
		            (field_id, meta_key, meta_value)
		            VALUES (%s, %s, %s)
		            ",
		            intval($data[0]),
		            sanitize_text_field($data[1]), 
		            sanitize_text_field($data[2])
		            
		            ) );

				}
			}
			//print_r($_POST['fieldids']); 
			$combined_array = array_map(function($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l) { 
				return $a.'-_-'.$b.'-_-'.$c.'-_-'.$d.'-_-'.$e.'-_-'.$f.'-_-'.$g.'-_-'.$h.'-_-'.$i.'-_-'.$j.'-_-'.$k.'-_-'.$l; 
			}, $fieldids, $fieldlabel, $fieldplaceholder, $fieldrequired, $fieldhidden, $fieldwidth, $fieldmessage, $showif, $cfield, $ccondition, $ccondition_value, $fieldreadonly);
			
			if($combined_array!='') {
				foreach ($combined_array as $value) {
					
					$data = explode('-_-', $value);
					$field_id = intval($data[0]);
					$field_label = sanitize_text_field($data[1]);
					$field_placeholder = sanitize_text_field($data[2]);
					$field_required = sanitize_text_field($data[3]);
					$field_hide = sanitize_text_field($data[4]);
					$field_width = sanitize_text_field($data[5]);
					$field_message = sanitize_text_field($data[6]);
					$show_if = sanitize_text_field($data[7]);
					$c_field = sanitize_text_field($data[8]);
					$c_condition = sanitize_text_field($data[9]);
					$c_condition_value = sanitize_text_field($data[10]);
					$field_readonly = sanitize_text_field($data[11]);

					$wpdb->query($wpdb->prepare(
						"UPDATE " .$wpdb->eorf_fields." SET field_label = %s, field_placeholder = %s, 
						is_required = %d, is_hide = %d, width = %s, field_message = %s, showif = %s, 
						cfield = %s, ccondition = %s, ccondition_value = %s, is_readonly = %s WHERE field_id = %d",
					    $field_label,
					    $field_placeholder,
					    $field_required,
					    $field_hide,
					    $field_width,
					    $field_message,
					    $show_if,
					    $c_field,
					    $c_condition,
					    $c_condition_value,
					    $field_readonly,
					    $field_id
					));

				}
			}

			die();
			return true;
		}



		function de_save_all_data() { 
			global $wpdb;


			if(isset($_POST['fieldids']) && $_POST['fieldids']!='') {
				$fieldids = $_POST['fieldids'];			
			} else { $fieldids = array(); }

			if(isset($_POST['fieldlabel']) && $_POST['fieldlabel']!='') {
				$fieldlabel = $_POST['fieldlabel'];			
			} else { $fieldlabel = array(); }

			if(isset($_POST['fieldplaceholder']) && $_POST['fieldplaceholder']!='') {
				$fieldplaceholder = $_POST['fieldplaceholder'];			
			} else { $fieldplaceholder = array(); }

			if(isset($_POST['fieldrequired']) && $_POST['fieldrequired']!='') {
				$fieldrequired = $_POST['fieldrequired'];			
			} else { $fieldrequired = array(); }
			
			if(isset($_POST['fieldwidth']) && $_POST['fieldwidth']!='') {
				$fieldwidth = $_POST['fieldwidth'];			
			} else { $fieldwidth = array(); }

			if(isset($_POST['fieldmessage']) && $_POST['fieldmessage']!='') {
				$fieldmessage = $_POST['fieldmessage'];			
			} else { $fieldmessage = array(); }

			if(isset($_POST['fieldstatus']) && $_POST['fieldstatus']!='') {
				$fieldstatus = $_POST['fieldstatus'];			
			} else { $fieldstatus = array(); }



			//print_r($_POST['fieldids']); 
			$combined_array = array_map(function($a, $b, $c, $d, $e, $f, $g) { 
				return $a.'-_-'.$b.'-_-'.$c.'-_-'.$d.'-_-'.$e.'-_-'.$f.'-_-'.$g; 
			}, $fieldids, $fieldlabel, $fieldplaceholder, $fieldrequired, $fieldwidth, $fieldmessage, $fieldstatus);
			
			if($combined_array!='') {
				foreach ($combined_array as $value) {
					
					$data = explode('-_-', $value);
					$field_id = intval($data[0]);
					$field_label = sanitize_text_field($data[1]);
					$field_placeholder = sanitize_text_field($data[2]);
					$field_required = sanitize_text_field($data[3]);
					$field_width = sanitize_text_field($data[4]);
					$field_message = sanitize_text_field($data[5]);
					$field_status = sanitize_text_field($data[6]);

					$wpdb->query($wpdb->prepare(
						"UPDATE " .$wpdb->eorf_de_fields." SET field_label = %s, field_placeholder = %s, 
						is_required = %d, width = %s, field_message = %s, field_status = %s WHERE field_id = %d",
					    $field_label,
					    $field_placeholder,
					    $field_required,
					    $field_width,
					    $field_message,
					    $field_status,
					    $field_id
					));

				}
			}

			die();
			return true;
		}



		public function getOptions($id) {
			global $wpdb;
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_meta." WHERE field_id = %d", $id));      
            return $result;
		}

		function eo_profile_fields() { ?>

			<h3><?php echo _e('Customer Profile', 'eorf'); ?></h3>
			<table class="form-table">
				<tbody>
					<?php 
						$fields = $this->get_reg_fields();
						foreach ($fields as $field) { 
					
						$value = get_user_meta( $_GET['user_id'], $field->field_name, true );

						if($field->field_type == 'text' && $field->is_hide == 0) {
					?>
							<tr id="a<?php echo $field->field_name; ?>" >
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<input type="text" class="regular-text" value="<?php echo $value; ?>" id="<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>">
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>

						<?php } else if($field->field_type == 'textarea' && $field->is_hide == 0) { ?>

							<tr id="a<?php echo $field->field_name; ?>" >
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<textarea cols="30" rows="5" id="<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>"><?php echo $value; ?></textarea>
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>

						<?php } else if($field->field_type == 'select' && $field->is_hide == 0) { ?>

							<tr id="a<?php echo $field->field_name; ?>" >
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<select style="min-width:200px;" id="<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>">
										<?php $options = $this->getOptions($field->field_id);
											foreach($options as $option) {
										?>
											<option value="<?php echo $option->meta_key; ?>" <?php if($option->meta_key == $value) { echo "selected"; } ?>>
												<?php echo $option->meta_value; ?>
											</option>

										<?php } ?>
									</select>
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>

						<?php } else if($field->field_type == 'multiselect' && $field->is_hide == 0) { ?>

							<tr id="a<?php echo $field->field_name; ?>" > 
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<select style="min-width:200px;min-height:150px;" multiple="true" id="<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>[]">
										<?php $options = $this->getOptions($field->field_id);
										
											foreach($options as $option) {

											$mdata = explode(', ',$value);

										?>

											<?php if(in_array($option->meta_key, $mdata)) { ?>

											<option value="<?php echo $option->meta_key; ?>" <?php echo "selected"; ?>>
												<?php echo $option->meta_value; ?>
											</option>

										<?php } else { ?>  

											<option value="<?php echo $option->meta_key; ?>">
												<?php echo $option->meta_value; ?>
											</option>

										<?php } } ?>
									</select>
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>

						<?php } else if($field->field_type == 'checkbox' && $field->is_hide == 0) { ?>

							<tr id="a<?php echo $field->field_name; ?>" > 
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<input type="checkbox" name="<?php echo $field->field_name; ?>" id="c<?php echo $field->field_name; ?>" value="<?php echo $value; ?>" <?php if($value == 1) { echo "checked"; } ?> />
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>

						<?php } else if($field->field_type == 'radioselect' && $field->is_hide == 0) { ?>

							<tr id="a<?php echo $field->field_name; ?>" > 
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<?php $options = $this->getOptions($field->field_id);
											foreach($options as $option) {
									?>

									<input type="radio" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php echo $option->meta_key; ?>" <?php if($option->meta_key == $value) { echo "checked"; } ?> /> <?php echo $option->meta_value; ?>
									
									<?php } ?>
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>

						<?php } else if($field->field_type == 'datepicker' && $field->is_hide == 0) { ?>

							<tr id="a<?php echo $field->field_name; ?>" >
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<input type="text" class="regular-text datepick" value="<?php echo $value; ?>" id="<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>">
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>

						<?php } else if($field->field_type == 'timepicker' && $field->is_hide == 0) { ?>

							<tr id="a<?php echo $field->field_name; ?>" >
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<input type="text" class="regular-text timepick" value="<?php echo $value; ?>" id="<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>">
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>

						<?php } else if($field->field_type == 'password' && $field->is_hide == 0) { ?>

							<tr id="a<?php echo $field->field_name; ?>" >
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<input type="password" class="regular-text" value="<?php echo $value; ?>" id="<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>">
									<br>
									<span class="description"></span>
								</td>
							</tr>

						<?php } else if($field->field_type == 'file' && $field->is_hide == 0) { ?>

							<tr id="a<?php echo $field->field_name; ?>" >
								<th><label for="<?php echo $field->field_name; ?>">Current <?php echo $field->field_label; ?></label></th>
								<td>
									<?php 
					
								$aa = EORF_URL.'uploaded_img/'.$value;
								$ext = pathinfo($aa, PATHINFO_EXTENSION);
								if($ext == 'pdf' || $ext == 'PDF') { ?>
									<a href="<?php echo EORF_URL; ?>uploaded_img/<?php echo $value; ?>" target="_blank">
										<img src="<?php echo EORF_URL; ?>images/pdf.png" width="100" height="100" title="Click to View" />
									</a>
								<?php } else { ?>
									<img src="<?php echo EORF_URL; ?>uploaded_img/<?php echo $value; ?>" width="100" height="100" />
								<?php } ?>
									<input type="hidden" class="regular-text" value="<?php echo $value; ?>" id="curr_<?php echo $field->field_name; ?>" name="curr_<?php echo $field->field_name; ?>">
								</td>
							</tr>

							<tr id="a<?php echo $field->field_name; ?>" >
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<input type="file" class="regular-text" id="<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>">
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>

						<?php } else if($field->field_type == 'numeric' && $field->is_hide == 0) { ?>
							<tr id="a<?php echo $field->field_name; ?>" >
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<input type="number" class="regular-text" value="<?php echo $value; ?>" id="<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>">
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>
						<?php } else if($field->field_type == 'color_picker') { ?>
							<tr id="a<?php echo $field->field_name; ?>" >
								<th><label for="<?php echo $field->field_name; ?>"><?php echo $field->field_label; ?></label></th>
								<td>
									<?php echo $value; ?>
									<input type="text" class="regular-text color_spectrum" value="<?php echo $value; ?>" id="<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>">
									<br>
									<span class="description"></span>
									<?php if(isset($field->field_message) && $field->field_message!='') { ?>
										<span class="description"><?php echo $field->field_message; ?></span>
									<?php } ?>
								</td>
							</tr>
							<script>
						
								jQuery(".color_spectrum").spectrum({
								    color: "<?php echo $value; ?>",
								    preferredFormat: "hex",
								});

							</script>
						<?php } ?>



						<script type="text/javascript">
	        		jQuery(document).ready(function($) { 
	        			
						<?php
							if($field->showif == 'Show') { ?>

								<?php if($field->ccondition == 'is not empty') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
										$("#a<?php echo $field->field_name ?>").show();
									});

									$("#a<?php echo $field->field_name ?>").show();

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }

										if(aa != '') {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}


								<?php } else if($field->ccondition == 'is equal to') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}

									

								<?php } else if($field->ccondition == 'is not equal to') { ?>

									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
								 	});


								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}


								<?php } else if($field->ccondition == 'is checked') { ?>

									$("#c<?php echo $field->cfield ?>").change(function(){ 

										if ($(this).is(':checked')) {
								           $("#a<?php echo $field->field_name ?>").show();
								        } else {
								        	$("#a<?php echo $field->field_name ?>").hide();
								        }
									});

									if ($("#c<?php echo $field->cfield ?>").is(':checked')) { 
										$("#a<?php echo $field->field_name ?>").show();
									} else {
										$("#a<?php echo $field->field_name ?>").hide();
									}



								<?php } ?>



			        			
			        		<?php } elseif($field->showif == 'Hide') { ?>

			        			<?php if($field->ccondition == 'is not empty') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
										$("#a<?php echo $field->field_name ?>").hide();
									});

									$("#a<?php echo $field->field_name ?>").hide();

									
									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }

										if(aa != '') {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
								 	});


								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


								<?php } else if($field->ccondition == 'is equal to') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


									

								<?php } else if($field->ccondition == 'is not equal to') { ?>

									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


									


								<?php } else if($field->ccondition == 'is checked') { ?>

									$("#c<?php echo $field->cfield ?>").change(function(){ 

										if ($(this).is(':checked')) {
								           $("#a<?php echo $field->field_name ?>").hide();
								        } else {
								        	$("#a<?php echo $field->field_name ?>").show();
								        }
									});


									if ($("#c<?php echo $field->cfield ?>").is(':checked')) { 
										$("#a<?php echo $field->field_name ?>").hide();
									} else {
										$("#a<?php echo $field->field_name ?>").show();
									}



								<?php } ?>
			        			
			        		<?php } else { ?>
			        			
			        		<?php } ?>
						
					});
	        	</script>



					<?php } ?>
				</tbody>
			</table>

		<?php }

		


		function eo_update_profile_fields($user_id) {
       		


       		$fields = $this->get_reg_fields();
        	foreach ($fields as $field) { 

        		if ( isset( $_POST[$field->field_name] ) || isset( $_FILES[$field->field_name] ) ) {

	        		if($field->field_type == 'file') {
	        			if($_FILES[$field->field_name]['name']!='') { 

								$filecheck = basename($_FILES[$field->field_name]['name']);
								$ext = strtolower(substr($filecheck, strrpos($filecheck, '.') + 1));


								if($_FILES[$field->field_name]["size"] > 49000000) {
									echo '<div class="error notice notice-success is-dismissible">';
									echo $field->field_name.'_error', __( $field->field_label.': Your file size is bigger than allowed size!', 'woocommerce' );
									echo '</div>';
									return false;

								} else if(!($ext == "jpg" || $ext == "gif" || $ext == "png" || $ext == "JPG" || $ext == "GIF" || $ext == "PNG" || $ext == "jpeg" || $ext == "JPEG"|| $ext == "PDF"|| $ext == "pdf")) {
									echo '<div class="error notice notice-success is-dismissible">';
									 echo $field->field_name.'_error', __( $field->field_label.': File Type not allowed!', 'woocommerce' );
									echo '</div>';
									return false;
									
								} else {
									

									$file = time('m').$_FILES[$field->field_name]['name'];
									$target_path = EORF_PLUGIN_DIR.'uploaded_img/';
									$target_path = $target_path . $file;
									$temp = move_uploaded_file($_FILES[$field->field_name]['tmp_name'], $target_path);
									update_user_meta($user_id,$field->field_name,sanitize_text_field($file));
								}
							} else {
								$file = $_POST['curr_'.$field->field_name];
								update_user_meta($user_id,$field->field_name,sanitize_text_field($file));
							}

						

	        		} else if($field->field_type == 'multiselect') { 
	        			$prefix = '';
	        			$multi = '';
	        			foreach ($_POST[$field->field_name] as $value) {
	        				$multi .= $prefix.$value;
    						$prefix = ', ';
	        			}
	        			update_user_meta( $user_id, $field->field_name, $multi );

	        		} else {

						update_user_meta( $user_id, $field->field_name, sanitize_text_field( $_POST[$field->field_name] ) );
					}

        		}
        	}


       }

	}

	new EO_Registration_Fields_Admin();
}