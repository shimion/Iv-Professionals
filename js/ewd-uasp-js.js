jQuery(document).ready( function() {
    jQuery('#ewd-uasp-das-find-appointment').on('click', UASP_AJAX_DAS_Get_Appointments);

    jQuery('#ewd-uasp-das-date').on('focus', UASP_Open_Picker);

    jQuery('.ewd-uasp-das-select').on('change', function() {
        var Selected_Location = jQuery('#ewd-uasp-das-location').val();
        var Selected_Service = jQuery('#ewd-uasp-das-service').val();
        var data = 'Location=' + Selected_Location + '&Service=' + Selected_Service + '&action=uasp_get_service_providers';

        jQuery.post(ajaxurl, data, function(response) {
            jQuery('#ewd-uasp-das-service-provider-input').html(response);
        });
    });

    jQuery('.ewd-uasp-appointment-form').submit(function(event) {
        if (!jQuery('#ewd-uasp-selected-appointment-time').val()) {
            jQuery('#ewd-uasp-das-appointment-times').html("Please select a valid appointment time before submitting the form.");
            event.preventDefault();
        }
    });
});

function UASP_AJAX_DAS_Get_Appointments() {
    var Selected_Location = jQuery('#ewd-uasp-das-location').val();
    var Selected_Service = jQuery('#ewd-uasp-das-service').val();
    var Selected_Service_Provider = jQuery('#ewd-uasp-das-service-provider').val();
    var Selected_Date = jQuery('#ewd-uasp-das-date').val();

    jQuery('#ufaq-ajax-results').html('<h3>Retrieving available appointments...</h3>');

    var data = 'Location=' + Selected_Location + '&Service=' + Selected_Service + '&Service_Provider=' + Selected_Service_Provider + '&Date=' + Selected_Date + '&action=uasp_get_appointments';
    jQuery.post(ajaxurl, data, function(response) {
        jQuery('#ewd-uasp-das-appointment-times').html(response);
    });
}

function SetAppointmentTime(ClickObject, AppointmentTime, ServiceProvider) {
    jQuery('.ewd-uasp-das-appointment-listing').each(function(){jQuery(this).removeClass('ewd-uasp-selected-appointment-time');})
    jQuery(ClickObject).parent().addClass('ewd-uasp-selected-appointment-time');
    
    var SelectedDate = jQuery('#ewd-uasp-das-date').val();
    jQuery('#ewd-uasp-selected-appointment-time').val(SelectedDate + " " + AppointmentTime);

    jQuery('#ewd-uasp-das-service-provider').val(ServiceProvider);
}

function ClearAppointments() {
    jQuery('#ewd-uasp-das-appointment-times').html("");
    jQuery('#ewd-uasp-selected-appointment-time').val("");
}

function UASP_Open_Picker() {
    var cal = document.querySelector('#ewd-uasp-das-date');
    var ev = document.createEvent('KeyboardEvent');
    ev.initKeyboardEvent('keydown', true, true, document.defaultView, 'F4', 0);
    cal.dispatchEvent(ev);
}