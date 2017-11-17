<?php
	$Appointment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_usap_appointments_table_name WHERE Appointment_ID=%d", $_GET['Appointment_ID']));
?>

<!-- Edit an appointment using the form below -->
<div id="col-left">
<div class="col-wrap">


<div class="form-wrap">
<h2><?php _e("Edit Appointment", 'ultimate-appointment-scheduling') ?></h2>

<form id="addtag" method="post" action="admin.php?page=EWD-UASP-options&Action=EWD_UASP_EditAppointment&DisplayPage=Appointments" class="validate" enctype="multipart/form-data">
<input type="hidden" name="action" value="Edit_Appointment" />
<input type="hidden" name="Appointment_ID" value="<?php echo $_GET['Appointment_ID']; ?>" />
<?php wp_nonce_field( 'EWD_UASP_Admin_Nonce', 'EWD_UASP_Admin_Nonce' );  ?>
<?php wp_referer_field(); ?>

<?php 
	$atts = array(
		'no_form' => 'Yes',
		'selected_appointment_id' => $Appointment->Appointment_ID
	);
	echo EWD_UASP_Dropdown_Appointment_Selector($atts); 
?>

<div class="form-field">
	<label for="Appointment_Client_Name"><?php _e("Client Name", 'ultimate-appointment-scheduling') ?></label>
	<input name="Appointment_Client_Name" id="Appointment_Client_Name" type="text" value="<?php echo $Appointment->Appointment_Client_Name;?>" size="60" />
	<p><?php _e("The name of the client that the appointment is being booked for.", 'ultimate-appointment-scheduling') ?></p>
</div>
<div>
	<label for="Appointment_Client_Phone"><?php _e("Client Phone", 'ultimate-appointment-scheduling') ?></label>
	<input name="Appointment_Client_Phone" id="Appointment_Client_Phone" type="text" value="<?php echo $Appointment->Appointment_Client_Phone;?>" size="60" />
	<p><?php _e("The phone number of the client that the appointment is being booked for.", 'ultimate-appointment-scheduling') ?></p>
</div>
<div>
	<label for="Appointment_Client_Email"><?php _e("Client Email", 'ultimate-appointment-scheduling') ?></label>
	<input name="Appointment_Client_Email" id="Appointment_Client_Email" type="text" value="<?php echo $Appointment->Appointment_Client_Email;?>" size="60" />
	<p><?php _e("The email of the client that the appointment is being booked for.", 'ultimate-appointment-scheduling') ?></p>
</div>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Edit Appointment', 'ultimate-appointment-scheduling') ?>"  /></p></form>

</div>
</div>
