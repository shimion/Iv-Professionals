<!-- List of the appointments which have already been created -->
<div id="col-right">
<div class="col-wrap">

<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>

<?php 
			if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
			else {$Page = 1;}
			
			$Sql = "SELECT * FROM $ewd_usap_appointments_table_name ";
				if (isset($_GET['OrderBy']) and $_GET['DisplayPage'] == "Dashboard") {$Sql .= "ORDER BY " . $_GET['OrderBy'] . " " . $_GET['Order'] . " ";}
				else {$Sql .= "ORDER BY Appointment_Start ";}
				$Sql .= "LIMIT " . ($Page - 1)*20 . ",20";
				$myrows = $wpdb->get_results($Sql);
				$TotalAppointments = $wpdb->get_results("SELECT Appointment_ID FROM $ewd_usap_appointments_table_name");
				$num_rows = $wpdb->num_rows; 
				$Number_of_Pages = ceil($wpdb->num_rows/20);
				$Current_Page_With_Order_By = "admin.php?page=EWD-UASP-options&DisplayPage=Appointments";
				if (isset($_GET['OrderBy'])) {$Current_Page_With_Order_By .= "&OrderBy=" .$_GET['OrderBy'] . "&Order=" . $_GET['Order'];}?>

<form action="admin.php?page=EWD-UASP-options&DisplayPage=Appointments&Action=EWD_UASP_MassDeleteAppointments" method="post">    
<div class="tablenav top">
		<div class="alignleft actions">
				<select name='action'>
  					<option value='-1' selected='selected'><?php _e("Bulk Actions", 'ultimate-appointment-scheduling') ?></option>
						<option value='delete'><?php _e("Delete", 'ultimate-appointment-scheduling') ?></option>
				</select>
				<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'ultimate-appointment-scheduling') ?>"  />
		</div>
		<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
				<span class="displaying-num"><?php echo $wpdb->num_rows; ?> <?php _e("items", 'ultimate-appointment-scheduling') ?></span>
				<span class='pagination-links'>
						<a class='first-page <?php if ($Page == 1) {echo "disabled";} ?>' title='Go to the first page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=1'>&laquo;</a>
						<a class='prev-page <?php if ($Page <= 1) {echo "disabled";} ?>' title='Go to the previous page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page-1;?>'>&lsaquo;</a>
						<span class="paging-input"><?php echo $Page; ?> <?php _e("of", 'ultimate-appointment-scheduling') ?> <span class='total-pages'><?php echo $Number_of_Pages; ?></span></span>
						<a class='next-page <?php if ($Page >= $Number_of_Pages) {echo "disabled";} ?>' title='Go to the next page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page+1;?>'>&rsaquo;</a>
						<a class='last-page <?php if ($Page == $Number_of_Pages) {echo "disabled";} ?>' title='Go to the last page' href='<?php echo $Current_Page_With_Order_By . "&Page=" . $Number_of_Pages; ?>'>&raquo;</a>
				</span>
		</div>
</div>

<table class="wp-list-table widefat fixed tags sorttable" cellspacing="0">
	<thead>
		<tr>
			<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
				<input type="checkbox" /></th><th scope='col' id='name' class='manage-column column-name sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Appointment_Client_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Client_Name&Order=DESC'>";}
				 	else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Client_Name&Order=ASC'>";} ?>
					<span><?php _e("Name", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='requirements' class='manage-column column-requirements sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Appointment_Client_Phone" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Client_Phone&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Client_Phone&Order=ASC'>";} ?>
					<span><?php _e("Phone", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='requirements' class='manage-column column-requirements sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Appointment_Start" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Start&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Start&Order=ASC'>";} ?>
					<span><?php _e("Date/Time", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='requirements' class='manage-column column-requirements sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Service_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Service_Name&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Service_Name&Order=ASC'>";} ?>
					<span><?php _e("Service", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='requirements' class='manage-column column-requirements sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Service_Provider_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Service_Provider_Name&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Service_Provider_Name&Order=ASC'>";} ?>
					<span><?php _e("Provider", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='requirements' class='manage-column column-requirements sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Location_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Location_Name&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Location_Name&Order=ASC'>";} ?>
					<span><?php _e("Location", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
				<input type="checkbox" /></th><th scope='col' id='name' class='manage-column column-name sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Appointment_Client_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Client_Name&Order=DESC'>";}
				 	else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Dashboartd&OrderBy=Appointment_Client_Name&Order=ASC'>";} ?>
					<span><?php _e("Name", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='requirements' class='manage-column column-requirements sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Appointment_Client_Phone" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Client_Phone&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Client_Phone&Order=ASC'>";} ?>
					<span><?php _e("Phone", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='requirements' class='manage-column column-requirements sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Appointment_Start" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Start&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Appointment_Start&Order=ASC'>";} ?>
					<span><?php _e("Date/Time", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='requirements' class='manage-column column-requirements sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Service_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Service_Name&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Service_Name&Order=ASC'>";} ?>
					<span><?php _e("Service", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='requirements' class='manage-column column-requirements sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Service_Provider_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Service_Provider_Name&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Service_Provider_Name&Order=ASC'>";} ?>
					<span><?php _e("Provider", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='requirements' class='manage-column column-requirements sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Location_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Location_Name&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UASP-options&DisplayPage=Appointments&OrderBy=Location_Name&Order=ASC'>";} ?>
					<span><?php _e("Location", 'ultimate-appointment-scheduling') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
		</tr>
	</tfoot>

	<tbody id="the-list" class='list:tag'>
		
		<?php
			if ($myrows) { 
	  			foreach ($myrows as $Appointment) {
					echo "<tr id='Item" . $Appointment->Appointment_ID ."'>";
					echo "<th scope='row' class='check-column'>";
					echo "<input type='checkbox' name='Appointments_Bulk[]' value='" . $Appointment->Appointment_ID ."' />";
					echo "</th>";
					echo "<td class='name column-name'>";
					echo "<strong>";
					echo "<a class='row-title' href='admin.php?page=EWD-UASP-options&Action=EWD_UASP_AppointmentDetails&Selected=Appointment&Appointment_ID=" . $Appointment->Appointment_ID ."' title='Edit " . $Appointment->Appointment_Client_Name . "'>" . $Appointment->Appointment_Client_Name . "</a></strong>";
					echo "<br />";
					echo "<div class='row-actions'>";
					echo "<span class='delete'>";
					echo "<a class='delete-tag' href='admin.php?page=EWD-UASP-options&Action=EWD_UASP_DeleteAppointment&DisplayPage=Appointment&Appointment_ID=" . $Appointment->Appointment_ID ."'>" . __("Delete", 'ultimate-appointment-scheduling') . "</a>";
		 			echo "</span>";
					echo "</div>";
					echo "<div class='hidden' id='inline_" . $Appointment->Appointment_ID ."'>";
					echo "<div class='name'>" . $Appointment->Appointment_Client_Name . "</div>";
					echo "</div>";
					echo "</td>";
					echo "<td class='email column-email'>" . $Appointment->Appointment_Client_Email . "</td>";
					echo "<td class='start column-start'>" . $Appointment->Appointment_Start . "</td>";
					echo "<td class='service column-service'>" . $Appointment->Service_Name . "</td>";
					echo "<td class='provider column-provider'>" . $Appointment->Service_Provider_Name . "</td>";
					echo "<td class='location column-location'>" . $Appointment->Location_Name . "</td>";
					echo "</tr>";
				}
			}
		?>

	</tbody>
</table>

<div class="tablenav bottom">
		<div class="alignleft actions">
				<select name='action'>
  					<option value='-1' selected='selected'><?php _e("Bulk Actions", 'ultimate-appointment-scheduling') ?></option>
						<option value='delete'><?php _e("Delete", 'ultimate-appointment-scheduling') ?></option>
				</select>
				<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'ultimate-appointment-scheduling') ?>"  />
		</div>
		<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
				<span class="displaying-num"><?php echo $wpdb->num_rows; ?> <?php _e("items", 'ultimate-appointment-scheduling') ?></span>
				<span class='pagination-links'>
						<a class='first-page <?php if ($Page == 1) {echo "disabled";} ?>' title='Go to the first page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=1'>&laquo;</a>
						<a class='prev-page <?php if ($Page <= 1) {echo "disabled";} ?>' title='Go to the previous page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page-1;?>'>&lsaquo;</a>
						<span class="paging-input"><?php echo $Page; ?> <?php _e("of", 'ultimate-appointment-scheduling') ?> <span class='total-pages'><?php echo $Number_of_Pages; ?></span></span>
						<a class='next-page <?php if ($Page >= $Number_of_Pages) {echo "disabled";} ?>' title='Go to the next page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page+1;?>'>&rsaquo;</a>
						<a class='last-page <?php if ($Page == $Number_of_Pages) {echo "disabled";} ?>' title='Go to the last page' href='<?php echo $Current_Page_With_Order_By . "&Page=" . $Number_of_Pages; ?>'>&raquo;</a>
				</span>
		</div>
		<br class="clear" />
</div>
</form>

<br class="clear" />
</div>
</div>

<!-- HTML for creating the appointments calendar -->

<!--<div id='ewd-uasp-calendar-blur'></div>
<div id='ewd-uasp-calendar-div'>
	<div id='ewd-uasp-calendar-selectors'><?php EWD_UASP_Return_Calendar_Select_Inputs(); ?></div>
	<div id='ewd-uasp-calendar'></div>
</div>-->

<!-- Add a new appointment using the form below -->
<div id="col-left">
<div class="col-wrap">


<div class="form-wrap">
<h3><?php _e("Add New Appointment", 'ultimate-appointment-scheduling') ?></h3>

<form id="addtag" method="post" action="admin.php?page=EWD-UASP-options&Action=EWD_UASP_AddAppointment&DisplayPage=Appointments" class="validate ewd-uasp-appointment-form" enctype="multipart/form-data">
<input type="hidden" name="action" value="Add_Appointment" />
<?php wp_nonce_field( 'EWD_UASP_Admin_Nonce', 'EWD_UASP_Admin_Nonce' );  ?>
<?php wp_referer_field(); ?>

<!--<div class='form-field'>
	<div class='ewd-uasp-appointment-scheduling-button'><?php _e("Schedule Appointment", 'ultimate-appointment-scheduling'); ?></div>
</div>-->
<?php 
	$atts = array('no_form' => 'Yes');
	echo EWD_UASP_Dropdown_Appointment_Selector($atts); 
?>

<div class="form-field">
	<label for="Appointment_Client_Name"><?php _e("Client Name", 'ultimate-appointment-scheduling') ?></label>
	<input name="Appointment_Client_Name" id="Appointment_Client_Name" type="text" value="" size="60" />
	<p><?php _e("The name of the client that the appointment is being booked for.", 'ultimate-appointment-scheduling') ?></p>
</div>
<div class="form-field">
	<label for="Appointment_Client_Phone"><?php _e("Client Phone", 'ultimate-appointment-scheduling') ?></label>
	<input name="Appointment_Client_Phone" id="Appointment_Client_Phone" type="text" value="" size="60" />
	<p><?php _e("The phone number of the client that the appointment is being booked for.", 'ultimate-appointment-scheduling') ?></p>
</div>
<div class="form-field">
	<label for="Appointment_Client_Email"><?php _e("Client Email", 'ultimate-appointment-scheduling') ?></label>
	<input name="Appointment_Client_Email" id="Appointment_Client_Email" type="text" value="" size="60" />
	<p><?php _e("The email of the client that the appointment is being booked for.", 'ultimate-appointment-scheduling') ?></p>
</div>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Add New Appointment', 'ultimate-appointment-scheduling') ?>"  /></p></form>

</div>

</div>
</div>
</div>