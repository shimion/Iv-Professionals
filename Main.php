<?php
/*
Plugin Name: Ultimate Appointment Scheduling
Plugin URI: http://www.EtoileWebDesign.com/wordpress-plugins/
Description: (This Plugin is customized Based on needed. Edited version is 0.23)A plugin that lets you schedule appointments, sync with Google calendar, send out automated reminders, etc.
Author: Tim Ruse
Author URI: http://www.EtoileWebDesign.com/wordpress-plugins/
Terms and Conditions: http://www.etoilewebdesign.com/plugin-terms-and-conditions/
Text Domain: ultimate-appointment-scheduling
Version: 10
*/

global $ewd_uasp_message;
global $EWD_UASP_Full_Version;
global $EWD_UASP_db_version;
global $ewd_usap_appointments_table_name, $ewd_usap_exceptions_table_name;
global $wpdb;

$EWD_UASP_db_version = "0.22";
//$wpdb->show_errors();

$ewd_usap_appointments_table_name = $wpdb->prefix . "EWD_UASP_Appointments";
$ewd_usap_exceptions_table_name = $wpdb->prefix . "EWD_UASP_Exceptions";

define( 'EWD_UASP_CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EWD_UASP_CD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

//define('WP_DEBUG', true);

register_activation_hook(__FILE__,'Install_EWD_UASP');
register_activation_hook(__FILE__,'Initial_EWD_UASP_Data');

/* Hooks neccessary admin tasks */
if ( is_admin() ){
		add_action('widgets_init', 'Update_EWD_UASP_Content');
		add_action('admin_head', 'EWD_UASP_Admin_Styles');
		add_action('admin_init', 'Add_EWD_UASP_Scripts');
		add_action('widgets_init', 'Update_EWD_UASP_Content');
		add_action('widgets_init', 'EWD_UASP_Possible_Email_Reminders');
		add_action('widgets_init', 'EWD_UASP_Check_Appointments');
		add_action('admin_notices', 'EWD_UASP_Error_Notices');
}

function EWD_UASP_Enable_Menu() {
	$Access_Role = get_option("EWD_UASP_Access_Role");
	if ($Access_Role == "") {$Access_Role = "administrator";}

	add_menu_page('Ultimate Appointment Scheduling', 'Appointments', $Access_Role, 'EWD-UASP-options', 'EWD_UASP_Output_Options', null , '50.9');
	//add_submenu_page('EWD-UASP-options', 'UASP Appointments', 'Appointments', $Access_Role, 'EWD-UASP-options&DisplayPage=Appointments', 'EWD_UASP_Output_Options');
	add_submenu_page('EWD-UASP-options', 'UASP Locations', 'Locations', $Access_Role, 'EWD-UASP-options&DisplayPage=Locations', 'EWD_UASP_Output_Options');
	add_submenu_page('EWD-UASP-options', 'UASP Services', 'Services', $Access_Role, 'EWD-UASP-options&DisplayPage=Services', 'EWD_UASP_Output_Options');
	add_submenu_page('EWD-UASP-options', 'UASP Service Providers', 'Providers', $Access_Role, 'EWD-UASP-options&DisplayPage=ServiceProviders', 'EWD_UASP_Output_Options');
	add_submenu_page('EWD-UASP-options', 'UASP Exceptions', 'Exceptions', $Access_Role, 'EWD-UASP-options&DisplayPage=Exceptions', 'EWD_UASP_Output_Options');
	add_submenu_page('EWD-UASP-options', 'UASP Settings', 'Settings', $Access_Role, 'EWD-UASP-options&DisplayPage=Settings', 'EWD_UASP_Output_Options');
}
add_action('admin_menu' , 'EWD_UASP_Enable_Menu');

/* Add localization support */
function EWD_UASP_localization_setup() {
		load_plugin_textdomain('ultimate-appointment-scheduling', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}
add_action('after_setup_theme', 'EWD_UASP_localization_setup');

// Add settings link on plugin page
function EWD_UASP_plugin_settings_link($links) {
  $settings_link = '<a href="admin.php?page=EWD-UASP-options">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'EWD_UASP_plugin_settings_link' );

function Add_EWD_UASP_Scripts() {
	$Time_Between_Appointments = get_option("EWD_UASP_Time_Between_Appointments");
	$Timezone = get_option("EWD_UASP_Timezone");
	$Email_Messages_Array = get_option("EWD_UASP_Email_Messages_Array");
	if (!is_array($Email_Messages_Array)) {$Email_Messages_Array = array();}
	
	if (isset($_GET['page']) && $_GET['page'] == 'EWD-UASP-options') {
		wp_register_script('EWD-UASP-Admin', plugins_url("ultimate-appointment-scheduling/js/Admin.js"), array('jquery'));
		wp_register_script('EWD-UASP-Calendar', plugins_url("ultimate-appointment-scheduling/js/ewd-uasp-calendar.js"), array('jquery', 'Moment-JS'));

		$Email_Data_Array = array('emails' => $Email_Messages_Array);
   		wp_localize_script('EWD-UASP-Admin', 'ewd_uasp_php_add_data', $Email_Data_Array);

   		$Calendar_Data_Array = array('time_interval' => $Time_Between_Appointments, 'timezone' => $Timezone);
   		wp_localize_script('EWD-UASP-Calendar', 'ewd_uasp_php_calendar_data', $Calendar_Data_Array);

		wp_enqueue_script('EWD-UASP-Admin');
		wp_enqueue_script('EWD-UASP-Calendar');
		wp_enqueue_script('Full-Calendar', plugins_url("ultimate-appointment-scheduling/js/fullcalendar.js"), array('jquery'), "1.0", true);
		wp_enqueue_script('jQuery-Custom-UI', plugins_url("ultimate-appointment-scheduling/js/jquery-ui.custom.min.js"), array('jquery'));
		wp_enqueue_script('Moment-JS', plugins_url("ultimate-appointment-scheduling/js/moment.min.js"), array('jquery'));
		wp_enqueue_script('ewd-uasp-js', plugins_url("ultimate-appointment-scheduling/js/ewd-uasp-js.js"), array('jquery'));
		wp_enqueue_script('Spectrum', plugins_url("ultimate-appointment-scheduling/js/spectrum.js"), array('jquery'));
	}
}

function EWD_UASP_Admin_Styles() {
	wp_enqueue_style( 'ewd-uasp-admin', plugins_url("ultimate-appointment-scheduling/css/Admin.css"));
	wp_enqueue_style( 'full-calendar', plugins_url("ultimate-appointment-scheduling/css/fullcalendar.css"));
	wp_enqueue_style( 'spectrum', plugins_url("ultimate-appointment-scheduling/css/spectrum.css"));
	//wp_enqueue_style( 'full-calendar-print', plugins_url("ultimate-appointment-scheduling/css/fullcalendar.print.css"));
}

add_action( 'wp_enqueue_scripts', 'Add_EWD_UASP_FrontEnd_Scripts' );
function Add_EWD_UASP_FrontEnd_Scripts() {
	$Time_Between_Appointments = get_option("EWD_UASP_Time_Between_Appointments");
	
	$Timezone = get_option("EWD_UASP_Timezone");
	$Time = new \DateTime('now', new DateTimeZone($Timezone));
	$Offset = $Time->format('P');

	$Minimum_Days_Advance = get_option("EWD_UASP_Minimum_Days_Advance");
	$Default_Date = date('Y-m-d', time() + $Minimum_Days_Advance *24*60*60);

	$Calendar_Starting_Layout = get_option("EWD_UASP_Calendar_Starting_Layout");
	$Calendar_Starting_Time = get_option("EWD_UASP_Calendar_Starting_Time") . ":00:00";

	wp_register_script('EWD-UASP-Calendar', plugins_url("ultimate-appointment-scheduling/js/ewd-uasp-calendar.js"), array('jquery', 'Moment-JS'));

   	$Calendar_Data_Array = array(
   		'time_interval' => $Time_Between_Appointments, 
   		'timezone' => $Timezone, 
   		'timezone_offset' => $Offset,
   		'default_date' => $Default_Date,
   		'starting_layout' => $Calendar_Starting_Layout,
   		'starting_time' => $Calendar_Starting_Time
   	);
   	wp_localize_script('EWD-UASP-Calendar', 'ewd_uasp_php_calendar_data', $Calendar_Data_Array);

	wp_enqueue_script('EWD-UASP-Calendar');
	wp_enqueue_script('ewd-uasp-js', plugins_url( '/js/ewd-uasp-js.js' , __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('Full-Calendar', plugins_url( '/js/fullcalendar.js' , __FILE__ ), array( 'jquery' ), "1.0", true);
	wp_enqueue_script('jQuery-Custom-UI', plugins_url( '/js/jquery-ui.custom.min.js' , __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('Moment-JS', plugins_url( '/js/moment.min.js' , __FILE__ ), array( 'jquery' ));
}


add_action( 'wp_enqueue_scripts', 'EWD_UASP_Add_Stylesheet' );
function EWD_UASP_Add_Stylesheet() {
    wp_enqueue_style( 'ewd-uasp-style', plugins_url('css/ewd-uasp-styles.css', __FILE__) );
    wp_enqueue_style( 'full-calendar', plugins_url("ultimate-appointment-scheduling/css/fullcalendar.css"));
}

add_action('activated_plugin','save_uasp_error');
function save_uasp_error(){
		update_option('plugin_error',  ob_get_contents());
		file_put_contents("Error.txt", ob_get_contents());
}

function EWD_UASP_Start_Session() {
	if (!session_id()) {session_start();}
}
add_action('init', 'EWD_UASP_Start_Session', 1);

$EWD_UASP_Full_Version = get_option("EWD_UASP_Full_Version");

/*if (isset($_POST['Upgrade_To_Full'])) {
	  add_action('admin_init', 'Upgrade_To_Full');
}*/

include "Functions/EWD_UASP_Add_Captcha.php";
include "Functions/EWD_UASP_Add_Views_Column.php";
include "Functions/EWD_UASP_Check_Appointments.php";
include "Functions/EWD_UASP_Error_Notices.php";
include "Functions/EWD_UASP_Facebook_Config.php";
include "Functions/EWD_UASP_Get_Events.php";
include "Functions/EWD_UASP_IPN.php";
include "Functions/EWD_UASP_Output_Buffering.php";
include "Functions/EWD_UASP_Output_Options.php";
include "Functions/EWD_UASP_Process_Ajax.php";
include "Functions/EWD_UASP_Reminders.php";
include "Functions/EWD_UASP_Return_Select_Options.php";
include "Functions/EWD_UASP_Twitter_Login.php";
include "Functions/EWD_UASP_WooCommerce_Sync.php";
include "Functions/FrontEndAjaxUrl.php";
include "Functions/Initial_EWD_UASP_Data.php";
include "Functions/Install_EWD_UASP.php";
include "Functions/Prepare_EWD_UASP_Data_For_Insertion.php";
include "Functions/Register_EWD_UASP_Posts_Taxonomies.php";
include "Functions/Update_EWD_UASP_Admin_Databases.php";
include "Functions/Update_EWD_UASP_Content.php";
include "Functions/Update_EWD_UASP_Tables.php";
include "Functions/EWD_UASP_Styling.php";

include "Shortcodes/EWD_UASP_Display_Calendar.php";
include "Shortcodes/EWD_UASP_Dropdown_Appointment_Selector.php";
include "Shortcodes/EWD_UASP_Appointment_Confirmation.php";
include "Shortcodes/EWD_UASP_Edit_Appointment.php";

// Updates the UASP database when required
if (get_option('EWD_UASP_DB_Version') != $EWD_UASP_db_version) {
	Update_EWD_UASP_Tables();
}


?>