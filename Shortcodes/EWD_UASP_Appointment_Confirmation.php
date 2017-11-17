<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function EWD_UASP_Appointment_Confirmation($atts) {
	global  $wpdb;
	global  $ewd_usap_appointments_table_name;
		
	$Custom_CSS = get_option('EWD_UASP_Custom_CSS');
		
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
		 		'confirmation_success_message' => __('Your appointment has been confirmed. Thank you!', 'ultimate-appointment-scheduling'),
		 		'confirmation_failure_message' => __('Your appointment could not be confirmed. Please contact the site administrator.', 'ultimate-appointment-scheduling')),
		$atts
		)
	);

	$Email = $_GET['Email'];
	$Appt_ID = $_GET['Appt_ID'];

	$wpdb->get_results($wpdb->prepare("SELECT Appointment_ID FROM $ewd_usap_appointments_table_name WHERE Appointment_Client_Email=%s AND Appointment_ID=%d", $Email, $Appt_ID));
	if ($wpdb->num_rows == 0) {return $confirmation_failure_message;}
	
	$wpdb->get_results($wpdb->prepare("UPDATE $ewd_usap_appointments_table_name SET Appointment_Confirmation_Received='Yes' WHERE Appointment_Client_Email=%s AND Appointment_ID=%d", $Email, $Appt_ID));

	return $confirmation_success_message;
}
add_shortcode("confirm-appointment", "EWD_UASP_Appointment_Confirmation");
