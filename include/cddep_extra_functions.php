<?php

/**
 * Get default wp editor for content.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('cddep_get_wp_editor')) {



	function cddep_get_wp_editor( $content = '', $editor_id, $options = array() ) {
		ob_start();

		wp_editor( $content, $editor_id, $options );

		
	}

}

/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('cddep_get_account_menu_item_classes')) {

	function cddep_get_account_menu_item_classes( $endpoint,$value ) {

		global $wp;

		$core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

		$icon_source       = isset($value['icon_source']) ? $value['icon_source'] : "default";

		switch($icon_source) {

			case "default":
			   $extra_li_class = '';
			break;

			case "noicon":
			   $extra_li_class = 'cddep_no_icon';
			break;

			case "custom":
			   $extra_li_class = 'cddep_custom_icon';
			break;

		}
        
        

        $classes = array(
        	'woocommerce-MyAccount-navigation-link',
        	'woocommerce-MyAccount-navigation-link--' . $endpoint,
        	''.$extra_li_class.''
        );
        
        
		

	    // Set current item class.
		$current = isset( $wp->query_vars[ $endpoint ] );
		if ( 'dashboard' === $endpoint && ( isset( $wp->query_vars['page'] ) || empty( $wp->query_vars ) ) ) {
		    $current = true; // Dashboard is not an endpoint, so needs a custom check.
	    } elseif ( 'orders' === $endpoint && isset( $wp->query_vars['view-order'] ) ) {
		    $current = true; // When looking at individual order, highlight Orders list item (to signify where in the menu the user currently is).
	    } elseif ( 'payment-methods' === $endpoint && isset( $wp->query_vars['add-payment-method'] ) ) {
		    $current = true;
	    }
 
	    if ( $current ) {
		    $classes[] = 'is-active';
	    }

	    $classes = apply_filters( 'woocommerce_account_menu_item_classes', $classes, $endpoint );

	    return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
    }
}


/**
 * Get account li html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('cddep_get_account_menu_li_html')) {

	function cddep_get_account_menu_li_html( $name , $key , $value ,$icon_extra_class,$extraclass,$icon_source) { ?>

		<li class="<?php echo cddep_get_account_menu_item_classes( $key , $value ); ?> <?php echo $extraclass; ?> <?php if ($icon_source == "custom") { echo $icon_extra_class; } ?>">
			<a href="<?php echo cddep_get_account_endpoint_url( $key ); ?>" <?php if (isset($value['cddep_type']) && ($value['cddep_type'] == "link") && (isset($value['link_targetblank'])) && ($value['link_targetblank'] == 01) ) { echo 'target="_blank"'; } ?>>
				<?php 
				if ($icon_source == "custom") {
					$icon       = isset($value['icon']) ? $value['icon'] : "";

					if ($icon != '') { ?>
						<i class="<?php echo $icon; ?>"></i>
					<?php }
				}
				?>
				<?php echo esc_html( $name ); ?>
			</a>
		</li>

	<?php }
}


/**
 * Dashboard Navigation menus
 *
 * @return array
 */
function cddep_dokan_get_dashboard_nav() {
    $menus = array(
        'dashboard' => array(
            'title'      => __( 'Dashboard', 'customize-dokan-dashboard-endpoints' ),
            'icon'       => '<i class="fas fa-tachometer-alt"></i>',
            'url'        => dokan_get_navigation_url(),
            'pos'        => 10,
            'permission' => 'dokan_view_overview_menu',
        ),
        'products' => array(
            'title'      => __( 'Products', 'customize-dokan-dashboard-endpoints' ),
            'icon'       => '<i class="fas fa-briefcase"></i>',
            'url'        => dokan_get_navigation_url( 'products' ),
            'pos'        => 30,
            'permission' => 'dokan_view_product_menu',
        ),
        'orders' => array(
            'title'      => __( 'Orders', 'customize-dokan-dashboard-endpoints' ),
            'icon'       => '<i class="fas fa-shopping-cart"></i>',
            'url'        => dokan_get_navigation_url( 'orders' ),
            'pos'        => 50,
            'permission' => 'dokan_view_order_menu',
        ),
        'withdraw' => array(
            'title'      => __( 'Withdraw', 'customize-dokan-dashboard-endpoints' ),
            'icon'       => '<i class="fas fa-upload"></i>',
            'url'        => dokan_get_navigation_url( 'withdraw' ),
            'pos'        => 70,
            'permission' => 'dokan_view_withdraw_menu',
        ),
        'settings' => array(
            'title' => __( 'Settings', 'customize-dokan-dashboard-endpoints' ),
            'icon'  => '<i class="fas fa-cog"></i>',
            'url'   => dokan_get_navigation_url( 'settings/store' ),
            'pos'   => 200,
        ),
        'store' => array(
            'title'      => __( 'Store', 'customize-dokan-dashboard-endpoints' ),
            'icon'       => '<i class="fas fa-university"></i>',
            'url'        => dokan_get_navigation_url( 'settings/store' ),
            'pos'        => 30,
            'permission' => 'dokan_view_store_settings_menu',
        ),
        'payment' => array(
            'title'      => __( 'Payment', 'customize-dokan-dashboard-endpoints' ),
            'icon'       => '<i class="far fa-credit-card"></i>',
            'url'        => dokan_get_navigation_url( 'settings/payment' ),
            'pos'        => 50,
            'permission' => 'dokan_view_store_payment_menu',
        ),
    );



    /**
     * Filter to get the seller dashboard settings navigation.
     *
     * @since 2.2
     *
     * @param array.
     */
    $menus['settings']['submenu'] = apply_filters( 'dokan_get_dashboard_settings_nav', $settings_sub );

    /**
     * Filters nav menu items.
     *
     * @param array<string,array> $menus
     */
    $nav_menus = apply_filters( 'dokan_get_dashboard_nav', $menus );

    foreach ( $nav_menus as $nav_key => $menu ) {
        if ( ! isset( $menu['pos'] ) ) {
            $nav_menus[ $nav_key ]['pos'] = 190;
        }

        $submenu_items = empty( $menu['submenu'] ) ? [] : $menu['submenu'];

        /**
         * Filters the vendor dashboard submenu item for each menu.
         *
         * @since 3.7.7
         *
         * @param array<string,array> $submenu_items Associative array of submenu items.
         * @param string              $menu_key      Key of the corresponding menu.
         */
        $submenu_items = apply_filters( 'dokan_dashboard_nav_submenu', $submenu_items, $nav_key );

        if ( empty( $submenu_items ) ) {
            continue;
        }

        foreach ( $submenu_items as $key => $submenu ) {
            if ( ! isset( $submenu['pos'] ) ) {
                $submenu['pos'] = 200;
            }

            $submenu_items[ $key ] = $submenu;
        }

        // Sort items according to positional value
        //uasort( $submenu_items, 'dokan_nav_sort_by_pos' );

        // Filter items according to permissions
        //$submenu_items = array_filter( $submenu_items, 'dokan_check_menu_permission' );

        // Manage menu with submenus after permission check
        if ( count( $submenu_items ) < 1 ) {
            unset( $nav_menus[ $nav_key ] );
        } else {
            $nav_menus[ $nav_key ]['submenu'] = $submenu_items;
        }
    }

    // Sort items according to positional value
    //uasort( $nav_menus, 'dokan_nav_sort_by_pos' );

    // Filter main menu according to permission
    //$nav_menus = array_filter( $nav_menus, 'dokan_check_menu_permission' );

    return $nav_menus;
}


/**
 * Get account li html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('cddep_get_account_endpoint_url')) {

	function cddep_get_account_endpoint_url($key) {

		$core_url = esc_url(wc_get_account_endpoint_url($key));

		return apply_filters('cddep_override_endpoint_url',$core_url,$key);

	}
}


/**
 * Get account group html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('cddep_get_account_menu_group_html')) {

	function cddep_get_account_menu_group_html( $name , $key , $value ,$icon_extra_class,$extraclass,$icon_source) { ?>

		<li class="<?php echo cddep_get_account_menu_item_classes( $key , $value ); ?> <?php echo $extraclass; ?> <?php if ($icon_source == "custom") { echo $icon_extra_class; } ?> <?php if (isset($value['group_open_default']) && ($value['group_open_default'] == "01" )) { echo 'open'; } else { echo 'closed'; } ?>">
			<a href="#" class="cddep_group">
				<?php 
				if ($icon_source == "custom") {
					$icon       = isset($value['icon']) ? $value['icon'] : "";

					if ($icon != '') { ?>
						<i class="<?php echo esc_url($icon); ?>"></i>
					<?php }
				}
				?>
				<?php echo esc_html( $name ); ?>
			</a>
			<?php
			$all_keys  = get_option('cddep_advanced_settings'); 
			$plugin_options = get_option('cddep_plugin_options'); 

			$matches   = cddep_get_child_li($all_keys, $key);


			$m_icon_position  = 'right';
            $m_icon_extra_class = '';

            if (isset($plugin_options['icon_position']) && ($plugin_options['icon_position'] != '')) {
            	$m_icon_position = $plugin_options['icon_position'];
            }



            switch($m_icon_position) {
            	case "right":
            	$m_icon_extra_class = "cddep_custom_right";
            	break;

            	case "left":
            	$m_icon_extra_class = "cddep_custom_left";
            	break;

            	default:
            	$m_icon_extra_class = "cddep_custom_right";
            	break;
            }
            
            
			

			if (sizeof($matches) > 0) { ?>
				<ul class="cddep_sub_level" style="<?php if (isset($value['group_open_default']) && ($value['group_open_default'] == "01" )) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">
					<?php
					foreach ($matches as $mkey=>$mvalue) {
						
						if (isset($mvalue['endpoint_name']) && ($mvalue['endpoint_name'] != '')) {
							$liname = $mvalue['endpoint_name'];
						} else {
							$liname = $mvalue;
						}

						$should_show = 'yes';



						if (isset($mvalue['show']) && ($mvalue['show'] == "no")) {

							$should_show = 'no';

						}

						$icon_source_child       = isset($mvalue['icon_source']) ? $mvalue['icon_source'] : "default";

						if (isset($mvalue['class']) && ($mvalue['class'] != '')) {
							$mextraclass = str_replace(',',' ', $mvalue['class']);
						} else {
							$mextraclass = '';
						}


						if ($should_show == "yes") {

							cddep_get_account_menu_li_html( $liname, $mkey ,$mvalue ,$m_icon_extra_class,$mextraclass,$icon_source_child );
					    }
					}
					?>
				</ul>
			<?php } ?>
			
		</li>

	<?php }
}


/**
 * Get parent li items.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('cddep_get_child_li')) {


	function cddep_get_child_li($array, $key) {

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

/**
 * Show user avatar before natigation items.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('cddep_myaccount_customer_avatar')) {

    function cddep_myaccount_customer_avatar() {
	    $current_user = wp_get_current_user();

	    $plugin_options = get_option('cddep_plugin_options');

	    $show_avatar    = isset($plugin_options['show_avatar']) ? $plugin_options['show_avatar'] : "no";
	    $avatar_size    = isset($plugin_options['avatar_size']) ? $plugin_options['avatar_size'] : 200;

	    if (isset($show_avatar) && ($show_avatar == "yes")) {
	    	echo '<div class="cddep_myaccount_avatar">' . get_avatar( $current_user->user_email, $avatar_size , '', $current_user->display_name ) . '</div>';
	    }
    }
}
 
add_action( 'cddep_before_account_navigation', 'cddep_myaccount_customer_avatar', 5 );


function wcmtxka_find_string_match($string,$array) {

	foreach ($array as $key=>$value) {

	$endpoint_key = $value['endpoint_key'];
    
    if ($endpoint_key == $string) { // Yoshi version
    	
    	return 'found';
    }
}

return 'notfound';


}

?>