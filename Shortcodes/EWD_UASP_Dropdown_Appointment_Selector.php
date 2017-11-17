<?php

function EWD_UASP_Dropdown_Appointment_Selector($atts) {
	global $woocommerce;
	global $wpdb;
	global $ewd_usap_appointments_table_name;
//	print_r($_REQUEST);
	//wp_mail('shimionson@gmail.com', 'Debug IV', json_encode($_REQUEST));
	$Default_Location_ID = get_option("EWD_UASP_Default_Location");
	$Default_Service_ID = get_option("EWD_UASP_Default_Service");
	$Default_Service_Provider_ID = get_option("EWD_UASP_Default_Service_Provider");

	$Minimum_Days_Advance = get_option("EWD_UASP_Minimum_Days_Advance");
	$Maximum_Days_Advance = get_option("EWD_UASP_Maximum_Days_Advance");

	$Add_Captcha = get_option("EWD_UASP_Add_Captcha");

	$Required_Information = get_option("EWD_UASP_Required_Information");
	if (!is_array($Required_Information)) {$Required_Information = array();}

	$Require_Login = get_option("EWD_UASP_Require_Login");

	$WooCommerce_Integration = get_option("EWD_UASP_WooCommerce_Integration");
	$Allow_Paypal_Prepayment = get_option("EWD_UASP_Allow_Paypal_Prepayment");
	$Pricing_Currency_Code = get_option("EWD_UASP_Pricing_Currency_Code");
	$PayPal_Email_Address = get_option("EWD_UASP_PayPal_Email_Address");
	$Thank_You_URL = get_option("EWD_UASP_Thank_You_URL");

	if (in_array("Name", $Required_Information)) {$Name_Required = "required";}
	else {$Name_Required = "";}
	if (in_array("Phone", $Required_Information)) {$Phone_Required = "required";}
	else {$Phone_Required = "";}
	if (in_array("Email", $Required_Information)) {$Email_Required = "required";}
	else {$Email_Required = "";}
	
		$Sign_Up_Title_Label = get_option("EWD_Sign_Up_Title_Label");
	if ($Sign_Up_Title_Label == "") {$Sign_Up_Title_Label = __("Sign Up", 'ultimate-appointment-scheduling');}
		$Name_Label = get_option("EWD_Name_Label");
	if ($Name_Label == "") {$Name_Label = __("Name", 'ultimate-appointment-scheduling');}
		$Phone_Label = get_option("EWD_Phone_Label");
	if ($Phone_Label == "") {$Phone_Label = __("Phone", 'ultimate-appointment-scheduling');}
		$Email_Label = get_option("EWD_Email_Label");
	if ($Email_Label == "") {$Email_Label = __("Email", 'ultimate-appointment-scheduling');}
		$Service_Title_Label = get_option("EWD_Service_Title_Label");
	if ($Service_Title_Label == "") {$Service_Title_Label = __("Service", 'ultimate-appointment-scheduling');}
		$Location_Label = get_option("EWD_Location_Label");
	if ($Location_Label == "") {$Location_Label = __("Location", 'ultimate-appointment-scheduling');}
		$Service_Label = get_option("EWD_Service_Label");
	if ($Service_Label == "") {$Service_Label = __("Service", 'ultimate-appointment-scheduling');}
		$Service_Provider_Label = get_option("EWD_Service_Provider_Label");
	if ($Service_Provider_Label == "") {$Service_Provider_Label = __("Service Provider", 'ultimate-appointment-scheduling');}
		$Appointment_Title_Label = get_option("EWD_Appointment_Title_Label");
	if ($Appointment_Title_Label == "") {$Appointment_Title_Label = __("Appointment", 'ultimate-appointment-scheduling');}
		$Appointment_Date_Label = get_option("EWD_Appointment_Date_Label");
	if ($Appointment_Date_Label == "") {$Appointment_Date_Label = __("Appointment Date", 'ultimate-appointment-scheduling');}
		$Find_Appointments_Label = get_option("EWD_Find_Appointments_Label");
	if ($Find_Appointments_Label == "") {$Find_Appointments_Label = __("Find Appointments", 'ultimate-appointment-scheduling');}
		$Book_Appointment_Label = get_option("EWD_Book_Appointment_Label");
	if ($Book_Appointment_Label == "") {$Book_Appointment_Label = __("Book Appointment", 'ultimate-appointment-scheduling');}
		$Pay_In_Advance_Label = get_option("EWD_Pay_In_Advance_Label");
	if ($Pay_In_Advance_Label == "") {$Pay_In_Advance_Label = __("Pay in Advance", 'ultimate-appointment-scheduling');}
		$Proceed_To_Payment_Label = get_option("EWD_Proceed_To_Payment_Label");
	if ($Proceed_To_Payment_Label == "") {$Proceed_To_Payment_Label = __("Proceed to Payment", 'ultimate-appointment-scheduling');}

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'no_form' => 'No',
				'redirect_page' => '#',
				'selected_appointment_id' => '',
				'selected_location_id' => '',
				'selected_service_id' => '',
				'selected_service_provider_id' => '',
				'selected_appointment_date' => '',
				'display_type' => 'Dropdown'
				),
		$atts
		)
	);
	
	if ($no_form != "Yes") {$Minimum_Date = date('Y-m-d',time()+$Minimum_Days_Advance*3600*24);}
	else {$Minimum_Date = "";}
	if ($no_form != "Yes") {$Maximum_Date = date('Y-m-d',time()+$Maximum_Days_Advance*3600*24);}
	else {$Maximum_Date = "";}

	if (isset($_POST['EWD_UASP_Appointment_Submit']) or isset($_POST['EWD_UASP_Appointment_Payment_Submit'])) {
	    $serviceID =  $_POST['Location_ID'];
	    if($serviceID == '369'){
	        $pro = '422';
	        }elseif($serviceID == '370'){
	        $pro = '428';
	        }elseif($serviceID == '574'){
	         $pro = '644';   
	   }
	    if(!empty($pro) or $pro != '0'){
	    $woocommerce->cart->add_to_cart($pro);
	    }
		$ewd_uasp_message = EWD_UASP_Add_Edit_Appointment();
		$WooCommerce_Integration ='Yes';
		if ($WooCommerce_Integration == "Yes" and isset($_POST['Service_ID']) and $ewd_uasp_message['Message_Type'] != 'Error') {
			$Service_ID = sanitize_text_field($_POST['Service_ID']);
			$args = array(
				'post_type' => 'product',
				'meta_query' => array(
					array(
						'key' => 'EWD_UASP_Service_ID',
						'value' => $Service_ID,
						'compare' => '='
					)
				)
			);
			$Service_Query = new WP_Query($args);
			if ($Service_Query->post_count > 0 and isset($woocommerce)) {
				$WC_Product = $Service_Query->posts[0];
				$woocommerce->cart->add_to_cart($WC_Product->ID);

				$WooCommerce_Checkout_Page_ID = get_option('woocommerce_cart_page_id');
				$Checkout_URL = get_permalink($WooCommerce_Checkout_Page_ID);
				wp_redirect($Checkout_URL);
				exit;
			}
		}
	}

	if ($ewd_uasp_message['Message'] == __("We have received your appointment request.", 'ultimate-appointment-scheduling') and !isset($_POST['EWD_UASP_Appointment_Payment_Submit']) and $redirect_page != '#') {header('location:'. $redirect_page);}

	//Create form with link to pay for appointment
	if (isset($_POST['EWD_UASP_Appointment_Payment_Submit'])) {
		$Selected_Appointment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_usap_appointments_table_name WHERE Appointment_ID=%d", $wpdb->insert_id));
		$Selected_Location = get_post($Selected_Appointment->Location_Post_ID);
		$Selected_Service = get_post($Selected_Appointment->Service_Post_ID);
		$Selected_Service_Provider = get_post($Selected_Appointment->Service_Provider_Post_ID);

		$ReturnString = "<div class='ewd-uasp-paypal-form'>";
		$ReturnString .= "<form action='https://www.paypal.com/cgi-bin/webscr' method='post' class='standard-form'>";
		//$ReturnString .= "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' class='standard-form'>";
        $ReturnString .= "<input type='hidden' name='item_name_1' value='" . $Selected_Service->post_title . "' />";
        $ReturnString .= "<input type='hidden' name='quantity_1' value='1' />";
        $ReturnString .= "<input type='hidden' name='amount_1' value='" . get_post_meta($Selected_Service->ID, 'Service Price', true) . "' />";
 		$ReturnString .= "<input type='hidden' name='custom' value='" . $Selected_Appointment->Appointment_ID . "' />";

    	$ReturnString .= "<input type='hidden' name='cmd' value='_cart' />";
    	$ReturnString .= "<input type='hidden' name='upload' value='1' />";
    	$ReturnString .= "<input type='hidden' name='business' value='" . $PayPal_Email_Address . "' />";
 
    	$ReturnString .= "<input type='hidden' name='currency_code' value='" . $Pricing_Currency_Code . "' />";
    	//$ReturnString .= "<input type='hidden' name='lc' value='CA' />"
    	//$ReturnString .= "<input type='hidden' name='rm' value='2' />";
    	$ReturnString .= "<input type='hidden' name='return' value='" . $Thank_You_URL . "' />";
    	//$ReturnString .= "<input type='hidden' name='cancel_return' value='" . ' />
    	$ReturnString .= "<input type='hidden' name='notify_url' value='" . get_site_url() . "' />";
 
    	$ReturnString .= "<input type='submit' class='submit-button' value='$Proceed_To_Payment_Label' />";
		$ReturnString .= "</form>";
		$ReturnString .= "</div>";

		return $ReturnString;
	}

	if ($Require_Login == "Yes" and $no_form != "Yes") {
		$Logged_In_User = EWD_UASP_Get_Login_Information();
		if ($Logged_In_User['Login_Status'] == "None") {
			$ReturnString .= "<div class='ewd-uasp-login-message'>";
			$ReturnString .= __("Please log in to book an appointment", 'ultimate-appointment-scheduling');
			$ReturnString .= "<br />";
			$ReturnString .= __("Login Options:", 'ultimate-appointment-scheduling');
			$ReturnString .= "</div>";
			$ReturnString .= "<div class='ewd-uasp-login-options'>";
			$ReturnString .= $Logged_In_User['ManageLogin'];
			$ReturnString .= "</div>";
			return $ReturnString;
		}
	}
	else {
		$Logged_In_User['Client_Name'] = "";
		$Logged_In_User['Client_Email'] = "";
		$Logged_In_User['Client_Phone'] = "";
	}

	if ($selected_appointment_id != "") {
		$Selected_Appointment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_usap_appointments_table_name WHERE Appointment_ID=%d", $selected_appointment_id));
		$selected_location_id = $Selected_Appointment->Location_Post_ID;
		$selected_service_id = $Selected_Appointment->Service_Post_ID;
		$selected_service_provider_id = $Selected_Appointment->Service_Provider_Post_ID;
		$Appointment_Time_Seconds = strtotime($Selected_Appointment->Appointment_Start);
		$selected_appointment_date = date("Y-m-d", $Appointment_Time_Seconds);
	}
	if ($selected_location_id != "") {$Default_Location_ID = $selected_location_id;}
	if ($selected_service_id != "") {$Default_Service_ID = $selected_service_id;}
	if ($selected_service_provider_id != "") {$Default_Service_Provider_ID = $selected_service_provider_id;}
	if ($selected_appointment_date != "") {$Default_Date = $selected_appointment_date;}

	if (isset($_GET['Location_ID']) and is_numeric($_GET['Location_ID'])) {$Default_Location_ID = $_GET['Location_ID'];}
	if (isset($_GET['Service_ID']) and is_numeric($_GET['Service_ID'])) {$Default_Service_ID = $_GET['Service_ID'];}
	if (isset($_GET['Service_Provider_ID']) and is_numeric($_GET['Service_Provider_ID'])) {$Default_Service_Provider_ID = $_GET['Service_Provider_ID'];}
	if (isset($_GET['Default_Date']) and $_GET['Default_Date'] != '') {$Default_Date = $_GET['Default_Date'];}

	$params = array(
		'posts_per_page' => -1, 
		'post_type' => 'uasp-location'
	);
	$Locations = get_posts($params);

	$params = array(
		'posts_per_page' => -1, 
		'post_type' => 'uasp-provider'
	);
	$Service_Providers = get_posts($params);

	$params = array(
		'posts_per_page' => -1, 
		'post_type' => 'uasp-service'
	);
	$Services = get_posts($params);

	$ReturnString = EWD_UASP_Add_Modified_Styles();
	$ReturnString .= "<div class='ewd-uasp-dropdown-appointment-selector'>";

	if (isset($ewd_uasp_message['Message'])) {
		$ReturnString .= "<div class='ewd-uasp-appointment-message ewd-uasp-" . $ewd_uasp_message['Message_Type'] . "'>";
		$massage = !empty($ewd_uasp_message['Message']) ? $ewd_uasp_message['Message'] : __("We have received your appointment request.", 'ultimate-appointment-scheduling');
		
		$download_message = 'Please download and fill out our intake and consent forms before your technician arrives. You can upload your completed forms through your My Account page.';
		
		$ReturnString .= sprintf("<h2 class='ssc'>%s</h2>", $massage);
		
		$ReturnString .= sprintf("<p class='ssc'>%s</p>", $download_message);
		
		$Selected_Appointment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_usap_appointments_table_name WHERE Appointment_ID=%d", $wpdb->insert_id));
		$Selected_Location = get_post($Selected_Appointment->Location_Post_ID);
		$Selected_Service = get_post($Selected_Appointment->Service_Post_ID);
		$Selected_Service_Provider = get_post($Selected_Appointment->Service_Provider_Post_ID);
	//	print_r($Selected_Location);
     //   print $Selected_Location->ID;
		$ReturnString .= "<div class='ewd-uasp-paypal-form'>";
		//$ReturnString .= "<form action='https://www.paypal.com/cgi-bin/webscr' method='post' class='standard-form'>";
		//$ReturnString .= "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' class='standard-form'>";
       // $ReturnString .= "<input type='hidden' name='item_name_1' value='" . $Selected_Service->post_title . "' />";
       // $ReturnString .= "<input type='hidden' name='quantity_1' value='1' />";
       // $ReturnString .= "<input type='hidden' name='amount_1' value='" . get_post_meta($Selected_Service->ID, 'Service Price', true) . "' />";
 	//	$ReturnString .= "<input type='hidden' name='custom' value='" . $Selected_Appointment->Appointment_ID . "' />";

    //	$ReturnString .= "<input type='hidden' name='cmd' value='_cart' />";
    //	$ReturnString .= "<input type='hidden' name='upload' value='1' />";
    //	$ReturnString .= "<input type='hidden' name='business' value='" . $PayPal_Email_Address . "' />";
 
    //	$ReturnString .= "<input type='hidden' name='currency_code' value='" . $Pricing_Currency_Code . "' />";
    	//$ReturnString .= "<input type='hidden' name='lc' value='CA' />"
    	//$ReturnString .= "<input type='hidden' name='rm' value='2' />";
    //	$ReturnString .= "<input type='hidden' name='return' value='" . $Thank_You_URL . "' />";
    	//$ReturnString .= "<input type='hidden' name='cancel_return' value='" . ' />
    //	$ReturnString .= "<input type='hidden' name='notify_url' value='" . get_site_url() . "' />";
 
    //	$ReturnString .= "<input type='submit' class='submit-button' value='$Proceed_To_Payment_Label' />";
	//	$ReturnString .= "</form>";
	   // if($Selected_Location->ID == '369'){
	    //    $pro = '422';
	    //    }elseif($Selected_Location->ID == '370'){
	     //   $pro = '428';
	   // }
	   // $ReturnString .=                                                 //ipe_add_to_cart_link($pro, 428, $Proceed_To_Payment_Label);
	   global $woocommerce;
	   $cart_data = WC()->cart->cart_contents_count;
	   if($cart_data != 0){
	    $ReturnString .= "<a href='/checkout/' class='hr_button' product-id='$pro' style='text-align: center;display: inline-block;max-width: 300px;float: none;margin: 0 auto !important'>$Proceed_To_Payment_Label</a>";
	    }
		$ReturnString .= "</div>";
		$no_form = 'Yes';
	}
	if ($no_form == "Yes") {
$ReturnString .=	'<style>
.ewd-uasp-das-registrationform-content, .ewd-uasp-das-findappointment{display: none;}
.ssc{text-align: center; display: block;}
 .ewd-uasp-das-container.ewd-uasp-das-service     , .title_bottom {display: none;}
 
 a.button.hr_button {
    display: block;
    margin: 0 auto !important;
    max-width: 300px;
    float: none;
    text-align: center;
}
#need_asap, div[data-id="7bj319c"]{display: none;}
#scheduling_text_widget, div[data-id="jjubbh4"]{display: none;}
#scheduling_title_widget, div[data-id="jjubbh4"]{display: none;}
        </style>';
	}

	if ($no_form != "Yes") {
		$ReturnString .= "<form action='#' method='post' class='ewd-uasp-appointment-form'>";
		if (!isset($selected_appointment_id) or $selected_appointment_id == "") {$ReturnString .= "<input type='hidden' name='action' value='EWD_UASP_AddAppointment'>";}
		else {$ReturnString .= "<input type='hidden' name='Appointment_ID' value='" . $selected_appointment_id . "'>";}
	}

    /*Sign up - Starts here*/
  	$ReturnString .= "<div class='ewd-uasp-das-container ewd-uasp-das-registrationform'>";
  	if ($no_form != "Yes") {$ReturnString .= "<div class='ewd-uasp-das-title ewd-uasp-das-registrationform-label'>" . $Sign_Up_Title_Label . "</div>";}
  	$ReturnString .= "<div class='ewd-uasp-das-registrationform-content'>";
	if ($no_form != "Yes") {
		$ReturnString .= "<div class='ewd-uasp-das-field'>";
	  	$ReturnString .= "<div class='ewd-uasp-das-name registration-field-title'>";
		$ReturnString .= "<label for='Appointment_Client_Name'>" .  $Name_Label . "</label>";
	  	$ReturnString .= "</div>";
		if ($Logged_In_User['Client_Name'] != "") {$ReturnString .= "<input name='Appointment_Client_Name' id='Appointment_Client_Name' type='hidden' value='" . $Logged_In_User['Client_Name'] . "' size='60' />" . $Logged_In_User['Client_Name'];}
		else {$ReturnString .= "<input name='Appointment_Client_Name' class='ewd-uasp-das-select' id='Appointment_Client_Name' type='text' value='" . $Selected_Appointment->Appointment_Client_Name . "' size='60' " . $Name_Required . "/>";}
		$ReturnString .= "</div>";
	  
		$ReturnString .= "<div class='ewd-uasp-das-field'>";
	  	$ReturnString .= "<div class='ewd-uasp-das-phone registration-field-title'>";
		$ReturnString .= "<label for='Appointment_Client_Phone'>" . $Phone_Label . "</label>";
	  	$ReturnString .= "</div>";
		if ($Logged_In_User['Client_Phone'] != "") {$ReturnString .= "<input name='Appointment_Client_Phone' id='Appointment_Client_Phone' type='tel' value='" . $Logged_In_User['Client_Phone'] . "' size='60' " . $Phone_Required . " />" . $Logged_In_User['Client_Phone'];}
		else {$ReturnString .= "<input name='Appointment_Client_Phone' class='ewd-uasp-das-select' id='Appointment_Client_Phone' type='tel' value='" . $Selected_Appointment->Appointment_Client_Phone . "' size='60' " . $Phone_Required . "/>";}
		$ReturnString .= "</div>";
	  
		$ReturnString .= "<div class='ewd-uasp-das-field'>";
	  	$ReturnString .= "<div class='ewd-uasp-das-email registration-field-title'>";
		$ReturnString .= "<label for='Appointment_Client_Email'>" . $Email_Label . "</label>";
	  	$ReturnString .= "</div>";
		if ($Logged_In_User['Client_Email'] != "") {$ReturnString .= "<input name='Appointment_Client_Email' id='Appointment_Client_Email' type='email' value='" . $Logged_In_User['Client_Email'] . "' size='60' " . $Email_Required . " />" . $Logged_In_User['Client_Email'];}
		else {$ReturnString .= "<input name='Appointment_Client_Email' class='ewd-uasp-das-select' id='Appointment_Client_Email' type='email' value='" . $Selected_Appointment->Appointment_Client_Email . "' size='60' " . $Email_Required . "/>";}
		
		        $ReturnString .= "<div class='ewd-uasp-das-field'>";
        $ReturnString .= "<div class='ewd-uasp-das-input-label' style='margin-bottom: 0;'><label>";
        $ReturnString .= $Location_Label;
        $ReturnString .= "</label></div>";

        $ReturnString .= "<select id='ewd-uasp-das-location' class='ewd-uasp-das-select' name='Location_ID' onchange='ClearAppointments();'>";
        
		foreach ($Locations as $Location) {
		   //edit 11/2/2017 
			//$ReturnString .= "<option value='" . $Location->ID . "' ";
		//	if ($Location->ID == $Default_Location_ID) {
		//	    $ReturnString .= "selected";
			}
		//	$ReturnString .= ">" . $Location->post_title . "</option>";
	//	}
	
	       $ReturnString .= '<option value="369">White Zone</option><option value="370">Blue Zone</option><option value="574">Genesee Town Center</option>';
	
	
	
	
		$ReturnString .= "</select>";
		$ReturnString .= "</div>";

		$ReturnString .= "</div>";
        
        
//        $ReturnString .= "<div class='ewd-uasp-das-field'>";
//        $ReturnString .= "<div class='ewd-uasp-das-input-label' style='margin-bottom: 0;'><label>";
//        $ReturnString .= $Location_Label;
//        $ReturnString .= "</label></div>";
//        
//        
//        $ReturnString .= "<select id='ewd-uasp-das-location' class='ewd-uasp-das-select' name='Location_ID' onchange='ClearAppointments();'>";
//		foreach ($Locations as $Location) {
//			$ReturnString .= "<option value='" . $Location->ID . "' ";
//			if ($Location->ID == $Default_Location_ID) {$ReturnString .= "selected";}
//			$ReturnString .= ">" . $Location->post_title . "</option>";
//		}
//		$ReturnString .= "</select>";
//        
//        $ReturnString .= "</div>";
       
       
	}
	if ($Logged_In_User['Login_Status'] != "None") {$ReturnString .= $Logged_In_User['ManageLogin'];}
    
    
    $ReturnString .= '<input type="hidden" id="ewd-uasp-das-service-provider" class="ewd-uasp-das-select" name="Service_Provider_ID" value="All">';
     
        
    $ReturnString .= '<input type="hidden" id="ewd-uasp-das-service" class="ewd-uasp-das-select" name="Service_ID"  value="371">
    ';
    
    $ServiceDuration = get_post_meta($Service[0]->ID, 'Service Duration', true);
		$ReturnString .= "<input type='hidden' id='hiddenservice' name='Service_ID' value='" . $Services[0]->ID . "'  data-serviceduration='30' />";
    
  	$ReturnString .= "</div>";
  	$ReturnString .= "</div>";
	
			$ReturnString .= "<div class='ewd-uasp-das-container ewd-uasp-das-service'>";
		
		$ReturnString .= "<a href='/wp-content/uploads/2017/11/IV-Professionals-Map-4.png'><img src='/wp-content/uploads/2017/11/IV-Professionals-Map-4-1.png' /></a><br><br>";
		$ReturnString .= "
<p class='hr_zone'><b>White Zone:</b> $50 concierge fee<br>
<b>Blue Zone:</b> $75 concierge fee<br>
<b>Genesee Town Center:</b> $50 reservation fee</p>

<p class='hr_zone'><b>Note:</b> If you do not fall in the white or blue zones but are still interested in our services, please contact us directly. IV Professionals is also available by appointment at our location in the Genesee Town Center in the Golden Foothills.</p>
<p class='hr_note'>Please provide us with the following information, and we'll be on our way!</p>";

		
				$ReturnString .= "</div>";


  /*Sign up - Ends here*/

  	/*Choose Service*/
	/*$ReturnString .= "<div class='ewd-uasp-das-container ewd-uasp-das-service'>";
  	if ($no_form != "Yes") {$ReturnString .= "<div class='ewd-uasp-das-title ewd-uasp-das-service-label'>" . $Service_Title_Label . "</div>";}
  	$ReturnString .= "<div class='ewd-uasp-das-service-content'>";
	$ReturnString .= "<div class='ewd-uasp-das-field'>";
	$ReturnString .= "<div class='ewd-uasp-das-input-label'>";
	$ReturnString .= $Location_Label;
	$ReturnString .= "</div>";
	$ReturnString .= "<div id='ewd-uasp-das-service-input' class='ewd-uasp-das-input'>";
	if (sizeof($Locations) == 1) {
		$ReturnString .= "<input type='hidden' id='ewd-uasp-das-location' name='Location_ID' value='" . $Locations[0]->ID . "' />";
		$ReturnString .= $Locations[0]->post_title;
	}
	else {
		$ReturnString .= "<select id='ewd-uasp-das-location' class='ewd-uasp-das-select' name='Location_ID' onchange='ClearAppointments();'>";
		foreach ($Locations as $Location) {
			$ReturnString .= "<option value='" . $Location->ID . "' ";
			if ($Location->ID == $Default_Location_ID) {$ReturnString .= "selected";}
			$ReturnString .= ">" . $Location->post_title . "</option>";
		}
		$ReturnString .= "</select>";
	}
	$ReturnString .= "</div>";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='clear'></div>";

	$ReturnString .= "<div class='ewd-uasp-das-field'>";
	$ReturnString .= "<div class='ewd-uasp-das-input-label'>";
	$ReturnString .= $Service_Label;
	$ReturnString .= "</div>";
	$ReturnString .= "<div id='ewd-uasp-das-service-input' class='ewd-uasp-das-input'>";
	if (sizeof($Services) == 1) {
		$ServiceDuration = get_post_meta($Service[0]->ID, 'Service Duration', true);
		$ReturnString .= "<input type='hidden' id='ewd-uasp-das-service' name='Service_ID' value='" . $Services[0]->ID . "'  data-serviceduration='" . $ServiceDuration . "' />";
		$ReturnString .= $Services[0]->post_title;
	}
	else {
		$ReturnString .= "<select id='ewd-uasp-das-service'  class='ewd-uasp-das-select' name='Service_ID' onchange='ClearAppointments();'>";
		foreach ($Services as $Service) {
			$ServiceDuration = get_post_meta($Service->ID, 'Service Duration', true);
			$ReturnString .= "<option value='" . $Service->ID . "' data-serviceduration='" . $ServiceDuration . "' ";
			if ($Service->ID == $Default_Service_ID) {$ReturnString .= "selected";}
			$ReturnString .= ">" . $Service->post_title . "</option>";
		}
		$ReturnString .= "</select>";
	}
	$ReturnString .= "</div>";
	$ReturnString .= "</div>";*/
/*	$ReturnString .= "<div class='clear'></div>";

	$ReturnString .= "<div class='ewd-uasp-das-field'>";
	$ReturnString .= "<div class='ewd-uasp-das-input-label'>";
	$ReturnString .= $Service_Provider_Label;
	$ReturnString .= "</div>";
	$ReturnString .= "<div id='ewd-uasp-das-service-provider-input' class='ewd-uasp-das-input'>";
	if (sizeof($Service_Providers) == 1) {
		$ReturnString .= "<input type='hidden' id='ewd-uasp-das-service-provider' name='Service_Provider_ID' value='" . $Service_Providers[0]->ID . "' />";
		$ReturnString .= $Service_Providers[0]->post_title;
	}
	else {
		$ReturnString .= "<select id='ewd-uasp-das-service-provider'  class='ewd-uasp-das-select' name='Service_Provider_ID' onchange='ClearAppointments();'>";
		$ReturnString .= "<option value='All'>" . __('Any', 'ultimate-appointment-scheduling') . "</option>";
		foreach ($Service_Providers as $Service_Provider) {
			$ReturnString .= "<option value='" . $Service_Provider->ID . "' ";
			if ($Service_Provider->ID == $Default_Service_Provider_ID) {$ReturnString .= "selected";}
			$ReturnString .= ">" . $Service_Provider->post_title . "</option>";
		}
		$ReturnString .= "</select>";
	}
  	$ReturnString .= "</div>";
  	$ReturnString .= "</div>";
  
	$ReturnString .= "</div>";
	$ReturnString .= "</div>";*/
	$ReturnString .= "<div class='clear'></div>";

  
  	/*Find Appointment*/
  	if ($display_type == 'Calendar' && $no_form != 'Yes') {
  		$ReturnString .= "<div id='ewd-uasp-calendar'></div>";
  		$ReturnString .= "<input type='hidden' name='Appointment_Start' id='ewd-uasp-selected-appointment-time' />";
  		$ReturnString .= "<div id='ewd-uasp-screen-background' class='ewd-uasp-hidden'></div>";
  		$ReturnString .= "<div id='ewd-uasp-time-select' class='ewd-uasp-hidden'>";
  		$ReturnString .= "<div id='ewd-uasp-time-location'></div>";
  	//	$ReturnString .= "<div id='ewd-uasp-time-service'></div>";
  $ReturnString .= "<div id='ewd-uasp-time-service-provider'></div>";
  		$ReturnString .= "<div id='ewd-uasp-time-select-input-div'>";
  	$ReturnString .= "<select name='time-select-input'></select>";
  		$ReturnString .= "</div>";
  		$ReturnString .= "<div id='ewd-uasp-select-time-button'>Select Time</div>";
  		$ReturnString .= "</div>";
  	}
  	else {
		$ReturnString .= "<div class='ewd-uasp-das-container ewd-uasp-das-findappointment'>";
  		if ($no_form != "Yes") {$ReturnString .= "<div class='ewd-uasp-das-title ewd-uasp-das-findappointment-label'>" . $Appointment_Title_Label . "</div>";}
  		$ReturnString .= "<div class='ewd-uasp-das-findappointment-content'>";
		$ReturnString .= "<div class='ewd-uasp-das-field'>";
		$ReturnString .= "<div class='ewd-uasp-das-input-label'>";
		$ReturnString .= $Appointment_Date_Label;
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='ewd-uasp-das-input'>";
		$ReturnString .= "<input id='ewd-uasp-das-date' class='ewd-uasp-das-select' type='date' name='Date' value='". $Default_Date . "' min='" . $Minimum_Date . "' max='" . $Maximum_Date . "' onchange='ClearAppointments();'/>";
		$ReturnString .= "</div>";
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='clear'></div>";

		$ReturnString .= "<div class='ewd-uasp-das-field'>";
		$ReturnString .= "<div class='ewd-uasp-das-input-label'>";
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='ewd-uasp-das-input'>";
  		$ReturnString .= "<div class='ewd-uasp-das-button-container'>";
		$ReturnString .= "<button type='button' id='ewd-uasp-das-find-appointment'>" . $Find_Appointments_Label . "</button>";
  		$ReturnString .= "</div>";
		$ReturnString .= "</div>";
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='clear'></div>";

		$ReturnString .= "<div id='ewd-uasp-das-appointment-times'>";
		if ($selected_appointment_id != "") {$ReturnString .= EWD_UASP_Get_Appointments_Times(373, 371, 421, 575, $selected_appointment_date, $selected_appointment_id);}
		$ReturnString .= "</div>";
  		$ReturnString .= "</div>";
  		$ReturnString .= "</div>";
  	}

  	$ReturnString .= "<div class='ewd-uasp-das-book-button-container'>"; 
  	if ($Add_Captcha == "Yes") {$ReturnString .= EWD_UASP_Add_Captcha();}
  	if ($no_form != "Yes") {
		if ($Allow_Paypal_Prepayment == "Required" or $Allow_Paypal_Prepayment == "Optional") {}
  		if ($Allow_Paypal_Prepayment == "Optional" or $Allow_Paypal_Prepayment == "No") {$ReturnString .= "<input type='submit' class='ewd-uasp-das-book-button' name='EWD_UASP_Appointment_Submit' value='" . $Book_Appointment_Label . "' />";}
  	}
  	$ReturnStrong .= "</div>";
	$ReturnString .= "<h3 class='title_bottom'>Book as a group of three or more and save!</h3>";
	$ReturnString .= "</form>";
  
	$ReturnString .= "</div>";

	return $ReturnString;
}
add_shortcode('ultimate-appointment-dropdown', "EWD_UASP_Dropdown_Appointment_Selector");


function EWD_UASP_Get_Login_Information() {
	$Login_Options = get_option("EWD_UASP_Login_Options");

	$WordPress_Login_URL = get_option("EWD_UASP_WordPress_Login_URL");
	$FEUP_Login_URL = get_option("EWD_UASP_FEUP_Login_URL");

	$Permalink = get_the_permalink();
	if (strpos($Permalink, "?") !== false) {$PageLink = $Permalink . "&";}
	else {$PageLink = $Permalink . "?";}

	$facebook = EWD_UASP_Facebook_Config();
	$fbuser = $facebook->getUser();
	$fbPermissions = 'public_profile';  //Required facebook permissions

	if (isset($_GET['Run_Login']) and $_GET['Run_Login'] == "Twitter" or isset($_GET['oauth_token'])) {EWD_UASP_Twitter_Login($PageLink);}

	$Facebook_Output = "";
	if (!$fbuser and in_array("Facebook", $Login_Options)) {
		$fbuser = null;
		$LoginURL = $facebook->getLoginUrl(array('redirect_uri'=>$Permalink,'scope'=>$fbPermissions));
		$Facebook_Output = "<div class='ewd-uasp-login-option' id='ewd-uasp-facebook-login'>";
		$Facebook_Output .= "<a href='".$LoginURL."'><img src='" . EWD_UASP_CD_PLUGIN_URL . "images/fb_login.png'></a>";
		$Facebook_Output .= "</div>";
	}
	else {
		$Facebook_Output = "<a href='" . $PageLink . "Logout=Facebook' >" . __("Logout", 'ultimate-appointment-scheduling') . "</a>";
	}

	$Twitter_Output = "";
	if ((!isset($_COOKIE['EWD_UASP_status']) && $_COOKIE['EWD_UASP_status'] != 'verified') and in_array("Twitter", $Login_Options)) {
		$Twitter_Output = "<div class='ewd-uasp-login-option' id='ewd-uasp-Twitter-login'>";
		$Twitter_Output .= "<a href='" . $PageLink . "Run_Login=Twitter'><img src='" . EWD_UASP_CD_PLUGIN_URL . "images/sign-in-with-twitter.png' width='151' height='24' border='0' /></a>";
		$Twitter_Output .= "</div>";
	}
	else {
		$Twitter_Output = "<a href='" . $PageLink . "Logout=Twitter' >" . __("Logout", 'ultimate-appointment-scheduling') . "</a>";
	}
	
	if (array_key_exists('Logout',$_GET)) {
		if ($_GET['Logout'] == "Facebook") {
			$facebook->destroySession();
			$Facebook_Output = __("You have been successfully logged out via Facebook. ", 'ultimate-appointment-scheduling');
			$Facebook_Output .= "<a href='" . $Permalink . "'>";
			$Facebook_Output .= __("Please reload the page", 'ultimate-appointment-scheduling');
			$Facebook_Output .= "</a>";
			$Facebook_Output .= " " . __("if you'd like to log in again", 'ultimate-appointment-scheduling');
		}
		if ($_GET['Logout'] == "Twitter") {
			EWD_UASP_Erase_Twitter_Data();
			$Twitter_Output = __("You have been successfully logged out via Twitter. ", 'ultimate-appointment-scheduling');
			$Twitter_Output .= "<a href='" . $Permalink . "'>";
			$Twitter_Output .= __("Please reload the page", 'ultimate-appointment-scheduling');
			$Twitter_Output .= "</a>";
			$Twitter_Output .= " " . __("if you'd like to log in again", 'ultimate-appointment-scheduling');
		}
	}

	if (function_exists("FEUP_User")) {
		$FEUP_User = new FEUP_User;
		$FEUP_Logged_In = $FEUP_User->Is_Logged_In();
	}
	else {
		$FEUP_Logged_In = false;
	}

	if ($fbuser and (!array_key_exists('Logout',$_GET) or $_GET['Logout'] != "Facebook")) {
		$Facebook_Logged_In = true;
	}

	if (isset($_COOKIE['EWD_UASP_status']) and $_COOKIE['EWD_UASP_status'] == 'verified'  and (!array_key_exists('Logout',$_GET) or $_GET['Logout'] != "Twitter")) {
		$Twitter_Logged_In = true;
	}

	if (in_array("WordPress", $Login_Options) and is_user_logged_in()) {
		$Logged_In_User['Login_Status'] = "WordPress";
		$User_Meta = array_map("EWD_UASP_WP_User_Array_Map", get_user_meta(get_current_user_id()));
		$Logged_In_User['Client_Name'] = $User_Meta['first_name'] . " " . $User_Meta['last_name'];
		$Logged_In_User['Client_Email'] = $User_Meta['email'];
		$Logged_In_User['Client_Phone'] = "";
	}
	elseif (in_array("FEUP", $Login_Options) and $FEUP_Logged_In) {
		$Logged_In_User['Login_Status'] = "FEUP";
		$Logged_In_User['Client_Name'] = "";
		$Logged_In_User['Client_Email'] = "";
		$Logged_In_User['Client_Phone'] = "";
	}
	elseif (in_array("Twitter", $Login_Options) and $Twitter_Logged_In) {
		$Logged_In_User['Login_Status'] = "Twitter";
		$Logged_In_User['Client_Name'] = $_COOKIE['EWD_UASP_Twitter_Full_Name'];
		$Logged_In_User['Client_Email'] = "";
		$Logged_In_User['Client_Phone'] = "";
	}
	elseif (in_array("Facebook", $Login_Options) and $Facebook_Logged_In) {
		$Logged_In_User['Login_Status'] = "Facebook";
		$user_profile = $facebook->api('/me?fields=name');
		if (!empty($user_profile)) {
			$Logged_In_User['Client_Name'] = $user_profile['name'];
		}
		$Logged_In_User['Client_Email'] = "";
		$Logged_In_User['Client_Phone'] = "";
	}
	else {
		$Logged_In_User['Login_Status'] = "None";
	}

	$Logged_In_User['ManageLogin'] = "";
	if (in_array("WordPress", $Login_Options) and $Logged_In_User['Login_Status'] == "None") {
		$Logged_In_User['ManageLogin'] .= "<div class='ewd-uasp-login-option' id='ewd-uasp-WordPress-login'>";
		$Logged_In_User['ManageLogin'] .= "<a href='" . $WordPress_Login_URL . "?EWD_UASP_Redirect_To=" . urlencode(get_the_permalink()) . "'>" . __("WordPress Login", 'ultimate-appointment-scheduling') . "</a>";
		$Logged_In_User['ManageLogin'] .= "</div>";
	}
	if (in_array("FEUP", $Login_Options) and $Logged_In_User['Login_Status'] == "None") {
		$Logged_In_User['ManageLogin'] .= "<div class='ewd-uasp-login-option' id='ewd-uasp-FEUP-login'>";
		$Logged_In_User['ManageLogin'] .= "<a href='" . $FEUP_Login_URL . "'>" . __("Login", 'ultimate-appointment-scheduling') . "</a>";
		$Logged_In_User['ManageLogin'] .= "</div>";
	}
	if (in_array("Facebook", $Login_Options) and ($Logged_In_User['Login_Status'] == "None" or $Logged_In_User['Login_Status'] == "Facebook")) {$Logged_In_User['ManageLogin'] .= $Facebook_Output;}
	if (in_array("Twitter", $Login_Options) and ($Logged_In_User['Login_Status'] == "None" or $Logged_In_User['Login_Status'] == "Twitter")) {$Logged_In_User['ManageLogin'] .= $Twitter_Output;}

	return $Logged_In_User;
}

function EWD_UASP_WP_User_Array_Map($a) {
	return $a[0];
}
