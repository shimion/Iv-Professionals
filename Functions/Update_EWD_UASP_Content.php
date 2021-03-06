<?php
/* This file is the action handler. The appropriate function is then called based 
*  on the action that's been selected by the user. The functions themselves are all
* stored either in Prepare_Data_For_Insertion.php or Update_Admin_Databases.php */
		
function Update_EWD_UASP_Content() {
	global $ewd_uasp_message;
	
	if (isset($_GET['Action'])) {
		switch ($_GET['Action']) {
			case "EWD_UASP_AddAppointment":
			case "EWD_UASP_EditAppointment":
       			$ewd_uasp_message = EWD_UASP_Add_Edit_Appointment();
				break;
			case "EWD_UASP_DeleteAppointment":
				$ewd_uasp_message = Delete_EWD_UASP_Appointment($_GET['Appointment_ID']);
				break;
			case "EWD_UASP_MassDeleteAppointment":
       			$ewd_uasp_message = EWD_UASP_Mass_Delete_Appointments();
				break;
			case "EWD_UASP_AddLocation":
			case "EWD_UASP_EditLocation":
       			$ewd_uasp_message = EWD_UASP_Add_Edit_Location();
				break;
			case "EWD_UASP_DeleteLocation":
       			$ewd_uasp_message = Delete_EWD_UASP_Location($_GET['Location_ID']);
				break;
			case "EWD_UASP_MassDeleteLocation":
       			$ewd_uasp_message = EWD_UASP_Mass_Delete_Locations();
				break;
			case "EWD_UASP_AddService":
			case "EWD_UASP_EditService":
       			$ewd_uasp_message = EWD_UASP_Add_Edit_Service();
				break;
			case "EWD_UASP_DeleteService":
       			$ewd_uasp_message = Delete_EWD_UASP_Service($_GET['Service_ID']);
				break;
			case "EWD_UASP_MassDeleteService":
       			$ewd_uasp_message = EWD_UASP_Mass_Delete_Services();
				break;
			case "EWD_UASP_AddServiceProvider":
			case "EWD_UASP_EditServiceProvider":
       			$ewd_uasp_message = EWD_UASP_Add_Edit_Service_Provider();
				break;
			case "EWD_UASP_DeleteServiceProvider":
       			$ewd_uasp_message = Delete_EWD_UASP_Service_Provider($_GET['Service_Provider_ID']);
				break;
			case "EWD_UASP_MassDeleteServiceProvider":
       			$ewd_uasp_message = EWD_UASP_Mass_Delete_Service_Providers();
				break;
			case "EWD_UASP_AddException":
			case "EWD_UASP_EditException":
       			$ewd_uasp_message = EWD_UASP_Add_Edit_Exception();
				break;
			case "EWD_UASP_DeleteException":
       			$ewd_uasp_message = Delete_EWD_UASP_Exception($_GET['Exception_ID']);
				break;
			case "EWD_UASP_MassDeleteException":
       			$ewd_uasp_message = EWD_UASP_Mass_Delete_Exceptions();
				break;
			case "EWD_UASP_UpdateOptions":
       			$ewd_uasp_message = EWD_UASP_UpdateOptions();
				break;
			default:
				$ewd_uasp_message = __("The form has not worked correctly. Please contact the plugin developer.", 'EWD_UFAQP');
				break;
		}
	}
}

?>