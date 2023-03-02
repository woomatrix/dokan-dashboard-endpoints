<?php 

if (!class_exists('cddep_add_frontend_class')) {

  class cddep_add_frontend_class {

    public function __construct() {
        add_filter( 'dokan_get_dashboard_nav', array( $this, 'dokan_get_dashboard_nav_function' ) ,200,1 );
        add_filter( 'dokan_query_var_filter', array( $this, 'dokan_query_var_filter_function' ) );
        add_action( 'dokan_load_custom_template', array( $this, 'dokan_load_custom_template_function' ) );
        add_action( 'wp_loaded', array($this,'wcmamtx_flush_rewrite_rules') );
        

    }


    public function wcmamtx_flush_rewrite_rules() {
        flush_rewrite_rules();
    }




    public function dokan_load_custom_template_function($query_vars) {
        $new_endpoints = (array) get_option('cddep_advanced_settings');
        

        
        foreach ($new_endpoints as $endkey=>$value ) {
            
            
            
            $endpoint_name = $value['endpoint_name'];

            $core_fields       = array('dashboard','products','orders','withdraw','settings','store','payment','followers','return-request','coupons','reviews','reports','tools','analytics','staffs','announcement','delivery-time-dashboard','support');

            if (isset($endpoint_name) && ($endpoint_name != "") && (!in_array($endkey, $core_fields))) {
                

                $endpoint_type = $value['cddep_type'];

                if ($endpoint_type == "endpoint") {

                    if ( isset( $query_vars[$endkey] )  && (!in_array($endkey, $core_fields))) {

                        $content = $value['content'];

                        

                            include 'template.php';

                        
                        
                        
                    }
                }
               

            }

            

        }
        
    }


    public function dokan_query_var_filter_function($query_vars) {
        $new_endpoints = (array) get_option('cddep_advanced_settings');
        
        
        
        foreach ($new_endpoints as $endkey=>$value ) {

            
            $endpoint_name = $value['endpoint_name'];

             $core_fields       = array('dashboard','products','orders','withdraw','settings','store','payment','followers','return-request','coupons','reviews','reports','tools','analytics','staffs','announcement','delivery-time-dashboard','support');

            if (isset($endpoint_name) && ($endpoint_name != "") && (!in_array($endkey, $core_fields))) {
                

                $endpoint_type = $value['cddep_type'];

                if (($endpoint_type == "endpoint") && (!in_array($endkey, $core_fields))) {

                     

                   

                    $query_vars[$endkey] = $endkey;

                    

                   
                }

                
               

            }

            

        }
        
        
        //print_r($query_vars);
        return $query_vars;
    }

    public function dokan_get_dashboard_nav_function($urls) {
        $new_endpoints = (array) get_option('cddep_advanced_settings');
        

        $posmatch = 100;

        foreach ($new_endpoints as $endkey=>$value ) {

            

            $endpoint_name = $value['endpoint_name'];

            if (isset($endpoint_name) && ($endpoint_name != "")) {


                $urls[$endkey]['title'] = $endpoint_name;

                $urls[$endkey]['permission'] = 'dokan_view_store_settings_menu';

                

                $endpoint_type = $value['cddep_type'];

                if ($endpoint_type == "link") {

                    $urls[$endkey]['url']   = $value['link_inputtarget'];

                } elseif ($endpoint_type == "endpoint") {

                    $urls[$endkey]['url']  = dokan_get_navigation_url($endkey);
                }

                
                $urls[$endkey]['pos']   = $posmatch;

                if (isset($value['link_inputtarget']) && ($value['icon'] != "")) {
                    $urls[$endkey]['icon'] = '<i class="'.$value['icon'].'"></i>';
                }

                if (isset($value['show']) && ($value['show'] == "no")) {
                    unset( $urls[$endkey] );
                }

            }

            $posmatch += 10;

        }

        

        return $urls;
    }


}

}



new cddep_add_frontend_class();

?>