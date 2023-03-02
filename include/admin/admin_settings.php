<?php
if (!class_exists('cddep_add_settings_page_class')) {

class cddep_add_settings_page_class {
	
	

	
	
	private $cddep_notices_settings_page = 'cddep_advanced_settings';
	
	private $cddep_plugin_settings_tab   = array();
	

	
	public function __construct() {
		add_action( 'init', array( $this, 'load_settings' ) );
		add_action( 'admin_init', array( $this, 'cddep_register_settings_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menus' ) ,100);
		add_action( 'admin_enqueue_scripts', array($this, 'cddep_register_admin_scripts'));
		add_action( 'admin_enqueue_scripts', array($this, 'cddep_load_admin_menu_style'));
        add_action( 'wp_ajax_restore_my_account_tabs', array( $this, 'restore_my_account_tabs' ) );
        add_action( 'wp_ajax_cddepadmin_add_new_value', array( $this, 'cddepadmin_add_new_value' ) );
        

		
	}


	


	public function cddep_load_admin_menu_style() {

	    wp_enqueue_style( 'woomatrix_admin_menu_css', ''.cddep_PLUGIN_URL.'assets/css/admin_menu.css' );
	    wp_enqueue_script( 'woomatrix_admin_menu_js', ''.cddep_PLUGIN_URL.'assets/js/admin_menu.js' );

	}



	public function cddepadmin_add_new_value() {

		/* First, check nonce */
        check_ajax_referer( 'cddep_nonce', 'security' );
        check_ajax_referer( 'cddep_nonce_hidden', 'nonce' );
		
		if (isset($_POST['row_type'])) {
			$row_type     = sanitize_text_field($_POST['row_type']);
		}
		
        if (isset($_POST['new_row'])) {
            $new_name      = sanitize_text_field($_POST['new_row']);
        }



        $random_number  = mt_rand(100000, 999999);
        $random_number2 = mt_rand(100000, 999999);



        switch($row_type) {
        	case "endpoint":
        	    $new_key   = 'custom-endpoint-'.$random_number.'';
        	break;

        	case "link":
        	    $new_key   = 'custom-link-'.$random_number.'';
            break;

        	case "group":
        	    $new_key   = 'custom-group-'.$random_number.'';
            break;

        	default:
        	    $new_key   = 'custom-endpoint-'.$random_number.'';
            break;
        }


        $new_row_values    =  (array) get_option('cddep_advanced_settings');

        $advancedsettings  = $this->advanced_settings;

        if (!isset($advancedsettings) || (sizeof($advancedsettings) == 1)) {
            $tabs  = cddep_dokan_get_dashboard_nav();

            foreach ($tabs as $key=>$value) {

            	if (($key == "store") || ($key == "payment")) {
        	      $cddep_parent = 'settings';

                } else {
                  $cddep_parent = 'none';
                }

                $endpnt_name = isset($value['endpoint_name']) ? $value['endpoint_name'] : ucfirst($key);
            
                $new_row_values[$key]['endpoint_key']        = $key;
                $new_row_values[$key]['endpoint_name']       = $endpnt_name;
                $new_row_values[$key]['cddep_type']          = 'endpoint';
                $new_row_values[$key]['parent']              = $cddep_parent;

                $new_row_values[$key]['class']               = isset($value['class']) ? $value['class'] : "";

                
                $new_row_values[$key]['visibleto']           = isset($value['visibleto']) ? $value['visibleto'] : "all";
                $new_row_values[$key]['roles']               = isset($value['roles']) ? $value['roles'] : array();
                $new_row_values[$key]['icon_source']         = isset($value['icon_source']) ? $value['icon_source'] : "default";
                $new_row_values[$key]['icon']                = isset($value['icon']) ? $value['icon'] : "";
                $new_row_values[$key]['content']             = isset($value['content']) ? $value['content'] : "";
                $new_row_values[$key]['show']                = isset($value['show']) ? $value['show'] : "yes";


            }

        } else {
        	

        	foreach ($advancedsettings as $key2=>$value2) {


        		if (($key == "store") || ($key == "payment")) {
        	      $cddep_parent = 'settings';

                } else {
                  $cddep_parent = $value2['parent'];
                }

                $endpnt_name = isset($value2['endpoint_name']) ? $value2['endpoint_name'] : "";
            
                $new_row_values[$key2]['endpoint_key']        = $key2;
                $new_row_values[$key2]['endpoint_name']       = $endpnt_name;
                $new_row_values[$key2]['cddep_type']          = $value2['cddep_type'];
                $new_row_values[$key2]['parent']              = $cddep_parent;
                
                $new_row_values[$key2]['class']               = isset($value2['class']) ? $value2['class'] : "";
                $new_row_values[$key2]['visibleto']           = isset($value2['visibleto']) ? $value2['visibleto'] : "all";
                $new_row_values[$key2]['roles']               = isset($value2['roles']) ? $value2['roles'] : array();
                $new_row_values[$key2]['icon_source']         = isset($value2['icon_source']) ? $value2['icon_source'] : "default";
                $new_row_values[$key2]['icon']                = isset($value2['icon']) ? $value2['icon'] : "";
                $new_row_values[$key2]['show']                = isset($value2['show']) ? $value2['show'] : "yes";
                

                if (isset($value2['cddep_type']) && ($value2['cddep_type'] == "link")) {
                	$new_row_values[$key2]['link_inputtarget']              = $value2['link_inputtarget'];
                	$new_row_values[$key2]['link_targetblank']              = $value2['link_targetblank'];
                }


                if (isset($value2['cddep_type']) && ($value2['cddep_type'] == "endpoint")) {
                    $new_row_values[$key2]['content']              = isset($value2['content']) ? $value2['content'] : "";
                }



                if (isset($value2['cddep_type']) && ($value2['cddep_type'] == "group")) {

                	$new_row_values[$key2]['group_open_default']   = isset($value2['group_open_default']) ? $value2['group_open_default'] : "no";

                }
                
            

            }

            if (isset($new_name) && ($new_name != '')) {
        	    $new_row_values[$new_key]['endpoint_key']        = $new_key;
                $new_row_values[$new_key]['endpoint_name']       = $new_name;
                $new_row_values[$new_key]['cddep_type']          = $row_type;
                $new_row_values[$new_key]['parent']              = 'none';

            }

        }






        

       

        

        if (($new_row_values != $advancedsettings) && !empty($new_row_values)) {
        	update_option('cddep_advanced_settings',$new_row_values);
        }



        die();
	}

	public function restore_my_account_tabs() {
	    if( current_user_can('editor') || current_user_can('administrator') ) {
	        delete_option( 'cddep_advanced_settings' );
	        
        } 
	   die();
	}
	
	
	public function load_settings() {
		
		$this->advanced_settings = (array) get_option( $this->cddep_notices_settings_page );
	    
	   

	}



	
	/*
	 * registers admin scripts via admin enqueue scripts
	 */
	public function cddep_register_admin_scripts($hook) {
	    global $general_cddepsettings_page;
			
		if ( $hook == $general_cddepsettings_page )  {

		    
 
            wp_enqueue_style( 'cddep_fontawesome', ''.cddep_PLUGIN_URL.'assets/css/font-awesome.min.css');

            
            wp_enqueue_script( 'cddep_bootstrap', ''.cddep_PLUGIN_URL.'assets/js/bootstrap.min.js');
            wp_enqueue_script( 'cddep_bootstrap_toggle', ''.cddep_PLUGIN_URL.'assets/js/bootstrap4-toggle.min.js');
            wp_enqueue_style( 'cddep_bootstrap', ''.cddep_PLUGIN_URL.'assets/css/bootstrap.min.css');
            wp_enqueue_style( 'cddep_bootstrap_toggle', ''.cddep_PLUGIN_URL.'assets/css/bootstrap4-toggle.min.css');

		    wp_enqueue_script( 'select2', ''.cddep_PLUGIN_URL.'assets/js/select2.js' );

		    wp_enqueue_script( 'cddepadmin', ''.cddep_PLUGIN_URL.'assets/js/admin.js',array('jquery-ui-accordion'), '1.0.0', true );
		
            wp_enqueue_script( 'cddep-tageditor', ''.cddep_PLUGIN_URL.'assets/js/tageditor.js');
		    wp_enqueue_style( 'cddep-tageditor', ''.cddep_PLUGIN_URL.'assets/css/tageditor.css');

	        wp_enqueue_style( 'jquery-ui-core', ''.cddep_PLUGIN_URL.'assets/css/jquery-ui.css' );
            wp_enqueue_style( 'select2',''.cddep_PLUGIN_URL.'assets/css/select2.css');
		 
		    wp_enqueue_style( 'cddepadmin', ''.cddep_PLUGIN_URL.'assets/css/admin.css' );


		 
		    $cddep_js_array = array(
                'new_row_alert_text'   => esc_html__( 'Enter name for new endpoint' ,'customize-dokan-dashboard-endpoints'),
                'new_group_alert_text' => esc_html__( 'Enter name for new group' ,'customize-dokan-dashboard-endpoints'),
                'new_link_alert_text'  => esc_html__( 'Enter name for new link' ,'customize-dokan-dashboard-endpoints'),
                'group_mixing_text'    => esc_html__( 'Group can not be dropped into group' ,'customize-dokan-dashboard-endpoints'),
                'setting_moveout'    => esc_html__( 'Moving out of settings child not supported yet.' ,'customize-dokan-dashboard-endpoints'),
                'setting_drop'    => esc_html__( 'Dropping endpoints into settings group not supported yet.' ,'customize-dokan-dashboard-endpoints'),
                'core_drop'    => esc_html__( 'Dropping of core fields into other group not supported yet.' ,'customize-dokan-dashboard-endpoints'),
                'restorealert'         => esc_html__( 'Are you sure you want to restore to default my account tabs ? you can not undo this.' ,'customize-dokan-dashboard-endpoints'),
                'endpoint_remove_alert'   => esc_html__( "Are you sure you want to delete this ?" ,'customize-dokan-dashboard-endpoints'),
                'settings_removal'   => esc_html__( "Removal of settings child not supported yet." ,'customize-dokan-dashboard-endpoints'),
                'core_remove_alert'     => esc_html__( "this group has core endpoints. please move them before removing this group" ,'customize-dokan-dashboard-endpoints'),
                'dt_type'               => cddep_get_version_type(),
                'pro_notice'            => esc_html__( 'This feature is available in pro version only.' ,'customize-dokan-dashboard-endpoints'),
                'empty_label_notice'    => esc_html__( 'Label can not be empty.' ,'customize-dokan-dashboard-endpoints'),
                'nonce'                 => wp_create_nonce( 'cddep_nonce' ),
                'ajax_url'              => admin_url( 'admin-ajax.php' ),
                'wait_text'             => esc_html__( 'Adding....' ,'customize-dokan-dashboard-endpoints')
                
            );

            wp_localize_script( 'cddepadmin', 'cddepadmin', $cddep_js_array );

        }
	}
	
	

	
	
	public function cddep_register_settings_settings() {

		$this->cddep_plugin_settings_tab[$this->cddep_notices_settings_page] = esc_html__( 'Dokan Endpoints' ,'customize-dokan-dashboard-endpoints');

		
       
        

		

		register_setting( $this->cddep_notices_settings_page, $this->cddep_notices_settings_page );

		add_settings_section( 'cddep_advance_section', '', '', $this->cddep_notices_settings_page );

		add_settings_field( 'advanced_option', '', array( $this, 'load_settings_form' ), $this->cddep_notices_settings_page, 'cddep_advance_section' );


	
		

	}



	/**
      * Recursive sanitation for an array
      * 
      * @param $array
      *
      * @return mixed
      */
	public function recursive_sanitize_text_field($array) {
		foreach ( $array as $key => $value ) {

			$value = sanitize_text_field( $value );

		}

		return $array;
	}
	

	

	

	/*
     * Linked product swatached settings
     * includes form field from forms folder
     */
	
	public function load_settings_form() { 

	   include ('forms/settings_form.php');
		   
	}





	
	
	/*
     * Adds Admin Menu "cart notices"
     * global $general_cddepsettings_page is used to include page specific scripts
     */

	public function add_admin_menus() {
	    global $general_cddepsettings_page;
        
        add_menu_page(
          __( 'sysbasics', 'customize-dokan-dashboard-endpoints' ),
         'SysBasics',
         'manage_woocommerce',
         'sysbasics',
         array($this,'plugin_options_page'),
         ''.cddep_PLUGIN_URL.'assets/images/icon.png',
         70
        );




	    

        $general_cddepsettings_page = add_submenu_page( 'sysbasics', cddep_PLUGIN_name , cddep_PLUGIN_name , 'manage_woocommerce', esc_html__($this->cddep_notices_settings_page), array($this, 'plugin_options_page'));


       


	         
	}







	public function plugin_options_page() {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : sanitize_text_field($this->cddep_notices_settings_page);
		$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $tab;
        $dt_type = cddep_get_version_type();

        
		
		?>
		<div class="wrap">
		   <?php $this->cddep_options_tab_wrap(); ?>
			<form method="post" action="options.php">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( $tab ); ?>
				<?php do_settings_sections( $tab ); ?>

				<div class="cddep_buttons_section">
				    
				    <?php if (isset($current_tab) && ($current_tab == "cddep_advanced_settings")) { ?>
				        <div class="cddep_add_section_div">
				            <button type="button" href="#" <?php if ($dt_type != "all") { echo 'data-bs-toggle="modal" data-bs-target="#cddep_example_modal"'; } ?> data-etype="endpoint" id="cddep_add_endpoint" class="btn btn-primary cddep_add_group <?php if ($dt_type == "all") { echo 'cddep_disabled'; } ?>">
				            	<span class="dashicons dashicons-insert"></span>
				            	<?php echo esc_html__( 'Add Endpont' ,'customize-dokan-dashboard-endpoints'); ?>
				            </button>

				            <button type="button" data-bs-toggle="modal" data-bs-target="#cddep_example_modal" data-etype="link" id="cddep_add_link" class="btn btn-primary cddep_add_group">
				            	<span class="dashicons dashicons-insert"></span>
				            	<?php echo esc_html__( 'Add Link' ,'customize-dokan-dashboard-endpoints'); ?>
				            </button>

				            

				            
				            
				        </div>
				        <div class="modal fade" id="cddep_example_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				        	<div class="modal-dialog" role="document">
				        		<div class="modal-content">
				        			
				        			<div class="modal-body">
				        				
				        				<div class="form-group">
				        					<input type="text" class="form-control" id="cddep_modal_label" placeholder="<?php echo esc_html__( 'Enter label' ,'customize-dokan-dashboard-endpoints'); ?>" value="">
				        					<input type="hidden" class="form-control" nonce="<?php echo wp_create_nonce( 'cddep_nonce_hidden' ); ?>" id="cddep_hidden_endpoint_type" placeholder="<?php echo esc_html__( 'Enter label' ,'customize-dokan-dashboard-endpoints'); ?>" value="">
				        				</div>
				        				<div class="alert alert-info cddep_enter_label_alert" role="alert" style="display:none;"></div>
				        				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo esc_html__( 'Close' ,'customize-dokan-dashboard-endpoints'); ?></button>
				        				<button type="submit" class="btn btn-primary cddep_new_end_point"><?php echo esc_html__( 'Add' ,'customize-dokan-dashboard-endpoints'); ?>
				        				    	
				        				</button>
				        				
				        			</div>
				        			<div class="modal-footer">
				        				
				        			</div>
				        		</div>
				        	</div>
				        </div>
				    <?php } ?>

                    <div class="cddep_submit_section_div">

				        <input type="submit" name="submit" id="submit" class="btn btn-success cddep_submit_button" value="<?php echo esc_html__( 'Save Changes' ,'customize-dokan-dashboard-endpoints'); ?>">

				        <?php if (isset($current_tab) && ($current_tab == "cddep_advanced_settings")) { ?>

				            <input type="button" href="#" name="submit" id="cddep_reset_tabs_button" class="btn btn-danger cddep_reset_tabs_button" value="<?php echo esc_html__( 'Restore Default' ,'customize-dokan-dashboard-endpoints'); ?>">
                            
                            



				            
				        <?php } ?>

				        <?php if (($dt_type == "all") && (cddep_pro_url != '')) { ?>
                                  
                            	<a type="button" target="_blank" href="<?php echo cddep_pro_url; ?>" name="submit" id="cddep_frontend_link" class="btn btn-primary cddep_frontend_link" >
                            		<span class="dashicons dashicons-lock"></span>
                            		<?php echo esc_html__( 'Upgrade to pro' ,'customize-dokan-dashboard-endpoints'); ?>
                            	</a>

                        <?php } ?>

				        <a type="button" target="_blank" href="<?php echo  esc_url( dokan_get_navigation_url() ); ?>" name="submit" id="cddep_frontend_link" class="btn btn-primary cddep_frontend_link" >
				        	    <span class="dashicons dashicons-welcome-view-site"></span>
				        	    <?php echo esc_html__( 'Frontend' ,'customize-dokan-dashboard-endpoints'); ?>
				        </a>

				    </div>

				    
				</div>
				
			</form>
		</div>
		<?php
	}


	
	public function cddep_options_tab_wrap() {

		$stab = sanitize_text_field($_GET['tab']);

		$current_tab = isset( $stab ) ? $stab : sanitize_text_field($this->cddep_notices_settings_page);

        echo '<h2 class="nav-tab-wrapper">';

		foreach ( $this->cddep_plugin_settings_tab as $tab_key => $tab_caption ) {

			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';

			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->cddep_notices_settings_page . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	

		}

		echo '</h2>';

	}

    /**
     * render accordion content from $key and $value
     */

	public function get_accordion_content($key,$name,$core_fields,$value = null,$old_value = null,$third_party = null) {
	     
	    $third_party = isset($value['third_party']) ? $value['third_party'] : $third_party; 

		if (isset($third_party)) {
			$key = strtolower($key);
			$key = str_replace(' ', '_', $key);
		} 


        
        ?>
        <li keyvalue="<?php echo $key; ?>" litype="<?php if (isset($value['cddep_type'])) { echo  $value['cddep_type']; } ?>" class="<?php if (isset($value['show']) && ($value['show'] == "no"))  { echo "cddep_disabled"; } ?> cddep_endpoint <?php echo $key; ?> <?php if (isset($value['cddep_type']) && ($value['cddep_type'] == "group")) { echo 'group'; } ?> <?php if (preg_match('/\b'.$key.'\b/', $core_fields )) { echo "core"; } ?>">
            

            
            <?php $this->get_main_li_content($key,$name,$core_fields,$value,$old_value,$third_party); ?>
            

        </li> <?php
        
    }











     public function get_main_li_content($key,$name,$core_fields,$value = null,$old_value = null,$third_party = null) { 
         
        global $wp_roles;

        

        $extra_content_core_fields = 'downloads,edit-address,edit-account';
        $exclude_content_core_fields       = 'dashboard,orders,customer-logout';

        if (isset($value['cddep_type'])) {

        	$cddep_type = $value['cddep_type'];

        }  else {
        	$cddep_type = 'endpoint';
       
        }


        if ($key == "settings") {
        	$cddep_type = 'group';

        }

        




        if (isset($value['parent']) && ($value['parent'] != "")) {

        	$cddep_parent = $value['parent'];
        	
        } else {

        	$cddep_parent = 'none';
       
        }


        if (($key == "store") || ($key == "payment")) {
        	$cddep_parent = 'settings';

        }
        


        if ( ! isset( $wp_roles ) ) { 
        	$wp_roles = new WP_Roles();  

        }

        $roles    = $wp_roles->roles;


        $third_party = isset($value['third_party']) ? $value['third_party'] : $third_party;

	    
    	?>

    	<h3>
    		<div class="cddep_accordion_handler">
    			<?php if (preg_match('/\b'.$key.'\b/', $core_fields )) { ?>
    				<input type="checkbox" class="cddep_accordion_onoff" parentkey="<?php echo $key; ?>"  <?php if (isset($value['show']) && ($value['show'] != "no"))  { echo "checked"; } elseif (!isset($value['show'])) { echo 'checked';} ?>>
    				<input type="hidden" class="<?php echo $key; ?>_hidden_checkbox" value='<?php if (isset($value['show']) && ($value['show'] == "no")) { echo "no"; } else { echo 'yes';} ?>' name='<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][show]'>

    			<?php } else { 
                      
    				if (isset($third_party)) {
    					$key = strtolower($key);
    					$key = str_replace(' ', '_', $key);
    				}

    				?>
    				<span type="removeicon" parentkey="<?php echo $key; ?>" class="dashicons dashicons-trash cddep_accordion_remove"></span>
    			<?php } ?>
    		</div>

    		<span class="dashicons dashicons-menu-alt "></span><?php if (isset($name)) { echo esc_attr($name); } ?>
    		<span class="cddep_type_label">
    			<?php echo ucfirst(esc_attr($cddep_type)); ?>
    		</span>

    	</h3>

        <div class="<?php echo $cddep_type; ?>_accordion_content">

        	<table class="cddep_table widefat">

        		<?php if (isset($third_party)) { ?>

        			<tr>
        				<td>
                        
        				</td>
        				<td>
        					<p><?php  echo esc_html__('This is third party endpoint.Some features may not work.','customize-dokan-dashboard-endpoints','customize-dokan-dashboard-endpoints'); ?></p>
        					<input type="hidden" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][third_party]" value="yes">
        					<input type="hidden" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][endpoint_name]" value="<?php if (isset($name)) { echo $name; } ?>">
        				</td>

        			</tr>

        		<?php } ?>

                <?php if ((!preg_match('/\b'.$key.'\b/', $core_fields ) && ($cddep_type == 'endpoint')) && (!isset($third_party))) { ?>   

                <tr>
                    <td>
                    	<label class="cddep_accordion_label"><?php  echo esc_html__('Key','customize-dokan-dashboard-endpoints','customize-dokan-dashboard-endpoints'); ?></label>
                    </td>
                    <td>
                        <input type="text" class="cddep_accordion_input" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][endpoint_key]" value="<?php if (isset($value['endpoint_key'])) { echo $value['endpoint_key']; } else { echo $key; } ?>">
                    </td>
            
                </tr>
                <?php } else { ?>

            	    <input type="hidden" class="cddep_accordion_input" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][endpoint_key]" value="<?php if (isset($value['endpoint_key'])) { echo $value['endpoint_key']; } else { echo $key; } ?>">


                <?php  } ?>

        
                <input type="hidden" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][cddep_type]" value="<?php echo $cddep_type; ?>">

                <input type="hidden" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][parent]" class="cddep_parent_field" value="<?php echo $cddep_parent; ?>">

                <?php if (!isset($third_party)) { ?>

                <tr>
                    <td>
                        <label class="cddep_accordion_label"><?php  echo esc_html__('Label','customize-dokan-dashboard-endpoints','customize-dokan-dashboard-endpoints'); ?></label>
                    </td>
                    <td>

                        <input type="text" class="cddep_accordion_input" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][endpoint_name]" value="<?php if (isset($value['endpoint_name'])) { echo $value['endpoint_name']; } else { echo ucfirst($key); } ?>">
                    </td>
            
                </tr>

                <?php } ?>
                

                <tr>
                    <td>
                        <label class="cddep_accordion_label"><?php  echo esc_html__('Icon Settings','customize-dokan-dashboard-endpoints'); ?></label>
                    </td>
                    <td>
                    	<?php 
                             if (isset($value['icon_source']) && ($value['icon_source'] != '')) {
                             	$icon_source = $value['icon_source'];
                             } else {
                             	$icon_source = 'default';
                             }
                    	?>

                    	<div class="cddep_icon_settings_div">
                    		<div class="form-check cddep_icon_checkbox">
                    			<input class="form-check-input cddep_icon_source_radio" type="radio" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][icon_source]"  value="default" <?php if ($icon_source == "default") { echo 'checked'; } ?>>
                    			<label class="form-check-label cddep_icon_checkbox_label" >
                    				<?php  echo esc_html__('Default theme Icon','customize-dokan-dashboard-endpoints'); ?>
                    			</label>
                    		</div>
                    		<div class="form-check cddep_icon_checkbox">
                    			<input class="form-check-input cddep_icon_source_radio" type="radio" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][icon_source]"  value="noicon" <?php if ($icon_source == "noicon") { echo 'checked'; } ?>>
                    			<label class="form-check-label cddep_icon_checkbox_label">
                    				<?php  echo esc_html__('No icon','customize-dokan-dashboard-endpoints'); ?>
                    			</label>
                    		</div>
                    		<div class="form-check cddep_icon_checkbox">
                    			<input class="form-check-input cddep_icon_source_radio" type="radio" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][icon_source]"  value="custom" <?php if ($icon_source == "custom") { echo 'checked'; } ?>>
                    			<label class="form-check-label cddep_icon_checkbox_label">
                    				<?php  echo esc_html__('Custom icon','customize-dokan-dashboard-endpoints'); ?>
                    			</label>
                    		</div>
                    	</div>
                    </td>
            
                </tr>

                <tr style= "<?php if ($icon_source == "custom") { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
                    <td>
                        <label class="cddep_accordion_label"><?php  echo esc_html__('Icon','customize-dokan-dashboard-endpoints'); ?></label>
                    </td>
                    <td>

                        <input type="text" class="cddep_iconpicker icon-class-input" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][icon]" value="<?php if (isset($value['icon'])) { echo $value['icon']; } ?>">
                        <button type="button" class="btn btn-primary picker-button"><?php  echo esc_html__('Pick an Icon','customize-dokan-dashboard-endpoints'); ?></button>
                    </td>
            
                </tr>
            

                <?php if ($cddep_type == 'link') {     
                ?>
                

                <tr>
                    <td>
                        <label class="cddep_accordion_label"><?php  echo esc_html__('Link url','customize-dokan-dashboard-endpoints'); ?></label>
                    </td>
                    <td>
                         <input class="cddep_accordion_input" type="text" name="cddep_advanced_settings[<?php echo $key; ?>][link_inputtarget]" value="<?php if (isset($value['link_inputtarget']) && ($value['link_inputtarget'] != '')) { echo ($value['link_inputtarget']); } else { echo '#';} ?>" size="70">
                    </td>
            
                </tr>

                

                <?php } ?>


                <tr>
			        <td>
                        <label class="cddepvisibleto cddep_accordion_label"><?php echo esc_html__('Visible to','customize-dokan-dashboard-endpoints'); ?></label>
	                </td>
			        <td>
			            <select class="cddepvisibleto" name="cddep_advanced_settings[<?php echo $key; ?>][visibleto]">
			                <option value="all" <?php if ((isset($value['visibleto'])) && ($value['visibleto'] == "all")) { echo "selected"; } ?>><?php echo esc_html__('All roles','customize-dokan-dashboard-endpoints'); ?></option>
				            <option value="specific" <?php if ((isset($value['visibleto'])) && ($value['visibleto'] == "specific")) { echo "selected"; } ?>><?php echo esc_html__('Specific roles','customize-dokan-dashboard-endpoints'); ?></option>
			            </select>
			   
	                </td>
			    </tr>

			    <?php 

			    if (!empty($value['roles'])) { 
			    	$chosenrolls = implode(',', $value['roles']); 
			    } else { 
			    	$chosenrolls=''; 
			    } 

			    ?>
			  
			    <tr style="<?php if ((isset($value['visibleto'])) && ($value['visibleto'] == "specific")) { echo "display:table-row;"; } else { echo "display:none;"; } ?>" class="cddeproles">
			        <td>
                        <label class="cddep_roles cddep_accordion_label"><?php echo esc_html__('Select roles','customize-dokan-dashboard-endpoints'); ?></label>
	                </td>
			        <td>
			            <select data-placeholder="<?php echo esc_html__('Choose Roles','customize-dokan-dashboard-endpoints'); ?>" name="cddep_advanced_settings[<?php echo $key; ?>][roles][]" class="cddep_roleselect" multiple>
                            <?php foreach ($roles as $rkey => $role) { ?>
				                <option value="<?php echo $rkey; ?>" <?php if (preg_match('/\b'.$rkey.'\b/', $chosenrolls )) { echo 'selected';}?>><?php echo $role['name']; ?></option>
				            <?php } ?>
                        </select>
                    </td>
			    </tr>


			    <?php if (($cddep_type == 'endpoint') && (!preg_match('/\b'.$key.'\b/', $exclude_content_core_fields )) && (!isset($third_party))) { ?>

			    <tr>
                    <td>
                        <label class="cddep_accordion_label cddep_custom_content_label"><?php  echo esc_html__('Custom Content','customize-dokan-dashboard-endpoints'); ?></label>
                    </td>
                    <td>    
                        
                        <?php 
                            $editor_content = isset($value['content']) ? $value['content'] : "";

                            

                            $editor_id      = 'cddep_content_'.$key.'';
                            $editor_name    = ''.esc_html__($this->cddep_notices_settings_page).'['.$key.'][content]';

                            wp_editor( $editor_content, $editor_id, $settings = array(
                            	'textarea_name' => $editor_name,
                            	'editor_height' => 180, // In pixels, takes precedence and has no default value
                                'textarea_rows' => 16
                            ) ); 
                        ?>
                    </td>
                </tr>

                <?php } ?>


                <?php if (($cddep_type == 'endpoint') && (preg_match('/\b'.$key.'\b/', $extra_content_core_fields ))) { ?>

                	<tr>
                		<td>
                			<label class="cddep_accordion_label"><?php  echo esc_html__('Content Settings','customize-dokan-dashboard-endpoints'); ?></label>
                		</td>
                		<td>
                			<?php 
                			if (isset($value['content_settings']) && ($value['content_settings'] != '')) {
                				$content_settings = $value['content_settings'];
                			} else {
                				$content_settings = 'after';
                			}
                			?>

                			<div class="cddep_content_settings_div">
                				<div class="form-check cddep_content_checkbox">
                					<input class="form-check-input cddep_content_source_radio" type="radio" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][content_settings]"  value="after" <?php if ($content_settings == "after") { echo 'checked'; } ?>>
                					<label class="form-check-label cddep_icon_checkbox_label" >
                						<?php  echo esc_html__('After Existing Content','customize-dokan-dashboard-endpoints','customize-dokan-dashboard-endpoints'); ?>
                					</label>
                				</div>
                				<div class="form-check cddep_content_checkbox">
                					<input class="form-check-input cddep_content_source_radio" type="radio" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][content_settings]"  value="before" <?php if ($content_settings == "before") { echo 'checked'; } ?>>
                					<label class="form-check-label cddep_icon_checkbox_label">
                						<?php  echo esc_html__('Before Existing Content','customize-dokan-dashboard-endpoints'); ?>
                					</label>
                				</div>
                			</div>
                		</td>

                	</tr>

                <?php } ?>


                <?php if ($cddep_type == 'group') { ?>

                	<tr>
                		<td>
                			<label class="cddep_accordion_label"><?php  echo esc_html__('Open by default','customize-dokan-dashboard-endpoints'); ?></label>
                		</td>
                		<td>    
                			<input class="cddep_accordion_input cddep_accordion_checkbox form-check-input" type="checkbox" name="cddep_advanced_settings[<?php echo $key; ?>][group_open_default]" <?php if (isset($value['group_open_default']) && ($value['group_open_default'] == "01")) { echo 'checked'; } ?> value="01">
                		</td>
                	</tr>

                <?php } ?>

                <tr>
                    <td>
                        <label class="cddep_accordion_label"><?php  echo esc_html__('Classes','customize-dokan-dashboard-endpoints'); ?></label>
                    </td>
                    <td>    
                        <input type="text" class="cddep_accordion_input cddep_class_input" name="<?php  echo esc_html__($this->cddep_notices_settings_page); ?>[<?php echo $key; ?>][class]" value="<?php if (isset($value['class'])) { echo $value['class']; } ?>">
                    </td>
                </tr>

                <?php if ($cddep_type != 'group') { ?>

                <?php } ?>

                
            </table>

        </div>

            <?php if (($cddep_type == 'group') && (($value['parent'] == "none") || ($key == "settings"))) {

            	$this->get_group_content($name,$key,$value);

                

            } ?>


    <?php 
    
    }


        public function get_group_content($name,$key,$value) {

        	    $all_keys  = $this->advanced_settings;  
                
                $matches   = $this->cddep_search($all_keys, $key);
                

         
    	    ?>

            	<ol class="cddep_group_items">

                    <?php 
                        foreach($matches as $mkey=>$mvalue) {
                        	$mname             = $mvalue['endpoint_name'];
                        	$core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';


                            $this->get_accordion_content($mkey,$mname,$core_fields,$mvalue,null);
                        }
                    ?>
                
                </ol>
            <?php
                
        }






        public function cddep_search($array, $key) {
          
            $results = array();

            
        
                foreach ($array as $subkey=>$subvalue) {

                	if (isset($subvalue['parent'])) {

                		if ($subvalue['parent'] == $key) {
                    	    $results[$subkey] = $subvalue;
                        }
                	}
                    
                }
            
            return $results;
        }
    


    }
}


new cddep_add_settings_page_class();
?>