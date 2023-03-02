<?php
/*
    Plugin Name: Customize Dokan Dashboard Endpoints
    Plugin URI: https://sysbasics.com
    Description: Customize Customize Dokan Dashboard Endpoints.
    Version: 1.0.0
    Author: SysBasics
    Author URI: https://sysbasics.com
    Domain Path: /languages
    Requires at least: 4.0
    Tested up to: 6.1.1
    WC requires at least: 4.0
    WC tested up to: 7.4.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly




if( !defined( 'cddep_plugin_slug' ) )
    define( 'cddep_plugin_slug', 'customize-dokan-dashboard-endpoints' );

if( !defined( 'cddep_PLUGIN_URL' ) )
    define( 'cddep_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


if( !defined( 'cddep_PLUGIN_name' ) )
    define( 'cddep_PLUGIN_name', esc_html__( 'Dokan Endpoints' ,'customize-dokan-dashboard-endpoints') );

if( !defined( 'cddep_update_doc_url' ) )
    define( 'cddep_update_doc_url', 'https://sysbasics.com/knowledge-base/how-to-update-woocommerce-color-or-image-variation-swatches-plugin/' );

if( !defined( 'cddep_doc_url' ) )
    define( 'cddep_doc_url', 'https://sysbasics.com/knowledge-base/' );

if( !defined( 'cddep_pro_url' ) )
    define( 'cddep_pro_url', 'https://www.sysbasics.com/downloads/customize-dokan-dashboard-endpoints-pro/' );

$mt_type = 'specific';
//these are not for users.thats why it is not translated.


load_plugin_textdomain( 'customize-dokan-dashboard-endpoints', false, basename( dirname(__FILE__) ).'/languages' );


//include the classes
include dirname( __FILE__ ) . '/include/admin/admin_settings.php';
include dirname( __FILE__ ) . '/include/frontend/woocommerce_frontend_endpoint.php';
include dirname( __FILE__ ) . '/include/frontend/add_content_frontend_login.php';
include dirname( __FILE__ ) . '/include/cddep_extra_functions.php';



if (!function_exists('cddep_placeholder_img_src')) {
    function cddep_placeholder_img_src() {
        return ''.cddep_PLUGIN_URL.'assets/images/placeholder.png';
    }

}




add_filter('sysbasics_deactivate_feedback_form_plugins', function($plugins) {

    $plugins[] = (object)array(
        'slug'      => cddep_plugin_slug,
        'version'   => cddep_get_plugin_version_number()
    );

    return $plugins;

});


/**
 * Get woocommerce version 
 */

if (!function_exists('cddep_get_woo_version_number')) {

    function cddep_get_woo_version_number() {
       
       if ( ! function_exists( 'get_plugins' ) )
         require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    
       
       $plugin_folder = get_plugins( '/' . 'woocommerce' );
       $plugin_file = 'woocommerce.php';
    
    
       if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
          return $plugin_folder[$plugin_file]['Version'];

       } else {
    
        return NULL;
       }
    }
}


/**
 * Get woocommerce version 
 */

if (!function_exists('cddep_get_plugin_version_number')) {

    function cddep_get_plugin_version_number() {
       
       if ( ! function_exists( 'get_plugins' ) )
         require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    
       
       $plugin_folder = get_plugins( '/' . ''.cddep_plugin_slug.'' );
       $plugin_file = ''.cddep_plugin_slug.'.php';
    
    
       if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
          return $plugin_folder[$plugin_file]['Version'];

       } else {
    
        return NULL;
       }
    }
}

register_activation_hook( __FILE__, 'cddep_subscriber_check_activation_hook' );

if (!function_exists('cddep_subscriber_check_activation_hook')) {

    function cddep_subscriber_check_activation_hook() {
        set_transient( 'cddep-admin-notice-activation', true, 5 );
    }
}




if (!function_exists('cddep_plugin_add_settings_link')) {

    function cddep_plugin_add_settings_link( $links ) {

        $mt_type = cddep_get_version_type();

        $settings_link1 = '<a href="' . admin_url( '/admin.php?page=cddep_advanced_settings' ) . '">' . esc_html__( 'Settings','customize-dokan-dashboard-endpoints' ) . '</a>';

        array_push( $links, $settings_link1 );

        if ( isset($mt_type) && ($mt_type == "specific")) {
            $settings_link2 = '<a href="'.cddep_update_doc_url.'">' . esc_html__( 'Enable dashboad updates','customize-dokan-dashboard-endpoints' ) . '</a>';
            array_push( $links, $settings_link2 );
        } else {
            $settings_link2 = '<a href="'.cddep_pro_url.'" style="color:green; font-weight:bold;">' . esc_html__( 'Upgrade to premium version','customize-dokan-dashboard-endpoints' ) . '</a>';
            array_push( $links, $settings_link2 );
        }

        
        return $links;
    }
}

$plugin = plugin_basename( __FILE__ );

add_filter( "plugin_action_links_$plugin", 'cddep_plugin_add_settings_link' );

if (!function_exists('cddep_plugin_row_meta')) {
    function cddep_plugin_row_meta( $links, $file ) {    
        if ( plugin_basename( __FILE__ ) == $file ) {
            $row_meta = array(
                'docs'    => '<a href="' . esc_url( cddep_doc_url ) . '" target="_blank" aria-label="' . esc_attr__( 'Docs', 'customize-dokan-dashboard-endpoints' ) . '" style="color:green;">' . esc_html__( 'Docs', 'customize-dokan-dashboard-endpoints' ) . '</a>',
                'support'    => '<a href="' . esc_url( 'https://sysbasics.com/support/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Support', 'customize-dokan-dashboard-endpoints' ) . '" style="color:green;">' . esc_html__( 'Support', 'customize-dokan-dashboard-endpoints' ) . '</a>'
            );
            return array_merge( $links, $row_meta );
        }
        return (array) $links;
    }
}

add_filter( 'plugin_row_meta', 'cddep_plugin_row_meta', 10, 2 );


if( !defined( 'cddep_version_type' ) )
    define( 'cddep_version_type', $mt_type );


if (!function_exists('cddep_plugin_path')) {

    function cddep_plugin_path() {
  
       return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

}


if (!function_exists('cddep_get_version_type')) {

    function cddep_get_version_type() {
        $plugin_path = plugin_dir_path( __FILE__ );

        if ((strpos($plugin_path, 'pro') !== false) && ( cddep_version_type == "specific")) { 
            $dt_type = 'specific';
            //these are not for users.thats why it is not translated.
        } else {
            $dt_type = 'all';
            //these are not for users.thats why it is not translated.
        }
    
        return $dt_type;
    }
}

$mt_type = cddep_get_version_type();

add_action( 'admin_notices', 'cddep_subscriber_check_activation_notice' );

if (!function_exists('cddep_subscriber_check_activation_notice')) {

    function cddep_subscriber_check_activation_notice(){
        
        if ( get_transient( 'cddep-admin-notice-activation' ) && isset($mt_type) && ($mt_type == "specific")) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html__( 'Thanks for purchasing '.cddep_PLUGIN_name.'.To enable dashboard updates ', 'customize-dokan-dashboard-endpoints' ); ?> <a href="<?php echo cddep_update_doc_url; ?>"><?php echo esc_html__( 'Follow this', 'customize-dokan-dashboard-endpoints' ); ?></a>.</p>
            </div>
            <?php
            delete_transient( 'cddep-admin-notice-activation' );
        }
    }
}
?>