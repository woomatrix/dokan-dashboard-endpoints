<?php 
      $advancedsettings  = $this->advanced_settings;  
      $tabs              = cddep_dokan_get_dashboard_nav();
      $core_fields       = '';
      
      $core_fields_array =  cddep_dokan_get_dashboard_nav();

      
      
    

      $core_fields       = 'dashboard,products,orders,withdraw,settings,store,payment,followers,return-request,coupons,reviews,reports,tools,analytics,staffs,announcement,delivery-time-dashboard,support';
      
      $core_fields_array =  array(
                         'dashboard'=>'dashboard',
                         'products'=>'products',
                         'orders'=>'orders',
                         'withdraw'=>'withdraw',
                         'settings'=>'settings',
                         'store'=>'store',
                         'payment'=>'payment',
                         'followers'=>'followers',
                         'return-request'=>'return-request'
                      );

     
      
if ((sizeof($advancedsettings) != 1) ) {

  foreach ($tabs as $ikey=>$ivalue) {

    $match = wcmtxka_find_string_match($ikey,$advancedsettings);

    if (!array_key_exists($ikey, $advancedsettings) && !array_key_exists($ikey, $core_fields_array) && ($match == "notfound")) {

      

      $advancedsettings[$ikey] = array(
        'show' => 'yes',
        'third_party' => 'yes',
        'endpoint_key' => $ikey,
        'cddep_type' => 'endpoint',
        'parent'       => 'none',
        'endpoint_name'=> $ivalue,
      );           

    }
  }



}
      


      

      if (!isset($advancedsettings) || (sizeof($advancedsettings) == 1)) {
        ?>
        <ol class="accordion cddep-accordion" style="display:none;">
            <?php
                $rownum = 0;
                foreach ($tabs as $key=>$value) {

                    if (preg_match('/\b'.$key.'\b/', $core_fields )) { 

                        $third_party = null;

                    } else {

                        $third_party = 'yes';

                    }


                    $title = $value['title'];

                    
                    
                    $this->get_accordion_content($key,$title,$core_fields,$value,null,$third_party); 
                    $rownum++;

                }
        ?>
        </ol><?php

         
      
      } else {


          
        ?>
        <ol class="accordion cddep-accordion" style="display:none;">
            <?php
                $rownum = 0;
                foreach ($advancedsettings as $key=>$value) {

                    

                    $name = isset($value['endpoint_name']) ? $value['endpoint_name'] : "";

                    $third_party = isset($value['third_party']) ? $value['third_party'] : null;



                    if (isset($value['parent']) && ($value['parent'] == "none")) {

                        $this->get_accordion_content($key,$name,$core_fields,$value,null,$third_party);
                    } 
                
                    $rownum++;

                }
        ?></ol><?php
      }
    ?>
        <script>
            var wmthash=<?php echo $rownum; ?>;
        </script>
        <div id="iconPicker" class="modal fade">
              <div class="modal-dialog">
                    <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title"><?php  echo esc_html__('Icon Picker','customize-dokan-dashboard-endpoints'); ?></h4>
                          </div>
                          <div class="modal-body">
                              <div>
                                  <ul class="icon-picker-list">
                                    <li>
                                      <a data-class="{{item}} {{activeState}}" data-index="{{index}}">
                                        <span class="{{item}}"></span>
                                         <span class="name-class">{{item}}</span>
                                      </a>
                                    </li>
                                  </ul>
                              </div>
                          </div>
                          <div class="modal-footer">
                                <button type="button" id="change-icon" class="btn btn-success">
                                  <span class="fa fa-check-circle-o"></span>
                                  <?php  echo esc_html__('Use Selected Icon','customize-dokan-dashboard-endpoints'); ?>
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                          </div>
                    </div>
              </div>
        </div>