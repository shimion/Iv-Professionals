jQuery(document).ready(function() {
    
    function srtvicepv($v, $default){
        if($v == 370){
            return 376;
        }else if($v == 369){
             return 373;
        }else if($v == 574){
             return 575;
        }else{
        return $default;
        }
    }
    
    
console.log(ajaxurl); console.log(ewd_uasp_php_calendar_data.timezone);
	jQuery('#ewd-uasp-calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay,listWeek'
		},
		defaultView: ewd_uasp_php_calendar_data.starting_layout,
        defaultDate: ewd_uasp_php_calendar_data.default_date,
		scrollTime : ewd_uasp_php_calendar_data.starting_time,
		editable: false,
		disableDragging: true,
		eventLimit: true, // allow "more" link when too many events
		timezone: ewd_uasp_php_calendar_data.timezone,
		events: function(start, end, timezone, callback) {
    	    jQuery.ajax({
    	        url: ajaxurl,
    	        data: {
    	            // our hypothetical feed requires UNIX timestamps
    	            action: 'uasp_get_events',
    	            start: start.unix(),
    	            end: end.unix(),
    	            location: jQuery('#ewd-uasp-das-location').val(),
    	            service: jQuery('#ewd-uasp-das-service').val(),
    	            service_provider: srtvicepv(jQuery('#ewd-uasp-das-location').val(), jQuery('#ewd-uasp-das-service-provider').val())
    	        },
    	        success: function(event_objects_json) {console.log(event_objects_json); 
    	        	event_objects = JSON.parse(event_objects_json);
    	        	console.log(event_objects);
    	            var events = [];
    	            jQuery(event_objects).each(function(index, item) {
    	                events.push({
    	                    title: item.title,
    	                    start: item.start,
    	                    end: item.end, // will be parsed
    	                    provider: item.provider
    	                });
    	            });
    	            callback(events);
    	        }
    	    });
    	},
    	eventClick: function(calEvent, jsEvent, view) {
        	var Time_Select  = jQuery('#ewd-uasp-time-select-input-div select');
        	Time_Select.find('option').remove();
        	
        	var Time = Date.parse(calEvent.start) / 1000;
        	var End_Time = Date.parse(calEvent.end) / 1000;
        	console.log(End_Time);
        	var Service_Duration = jQuery('#hiddenservice').data('serviceduration');
        	
        	jQuery('#ewd-uasp-time-select').data('date', calEvent.start);
        	jQuery('#ewd-uasp-time-select').data('provider', calEvent.provider);

        	var Offset = ewd_uasp_php_calendar_data.timezone_offset;
        	var Offset_Parts = Offset.split(':');
        	var Offset_Seconds = (parseInt(Offset_Parts[0] * -1 * 60) + parseInt(Offset_Parts[1])) * 60;

       		while (Time < (End_Time - (Service_Duration*60))) { 
        		var Appointment_Time = new Date((Time + Offset_Seconds)*1000);
				
				//console.log(Appointment_Time);
        		jQuery('#ewd-uasp-time-select-input-div select').append('<option value="' + Time + '">' + ( Appointment_Time.toLocaleTimeString()) + '</option>');
        		Time += (ewd_uasp_php_calendar_data.time_interval * 60);
        	}

            jQuery('.ewd-uasp-selected-event').removeClass('ewd-uasp-selected-event');
            jQuery(this).addClass('ewd-uasp-selected-event');

        	jQuery('#ewd-uasp-time-location').html('Location: ' + jQuery('#ewd-uasp-das-location option:selected').text());
        	jQuery('#ewd-uasp-time-service').html('Service: ' + jQuery('#ewd-uasp-das-service option:selected').text());
        	jQuery('#ewd-uasp-time-service-provider').html('Service Provider: ' + jQuery('#ewd-uasp-das-service-provider option[value="' + calEvent.provider + '"]').text());
        	
        	jQuery('#ewd-uasp-time-select, #ewd-uasp-screen-background').removeClass('ewd-uasp-hidden');
        	console.log(calEvent);
        	console.log(jsEvent);
        	console.log(view);
    	},
	});

	jQuery('#ewd-uasp-das-location').on('change', function() {
		jQuery('#ewd-uasp-calendar').fullCalendar('refetchEvents');
	});
	jQuery('#ewd-uasp-das-service').on('change', function() {
		jQuery('#ewd-uasp-calendar').fullCalendar('refetchEvents');
	});
	jQuery('#ewd-uasp-das-service-provider').on('change', function() {
		jQuery('#ewd-uasp-calendar').fullCalendar('refetchEvents');
	});

    jQuery('#ewd-uasp-screen-background').on('click', function() {
        jQuery('#ewd-uasp-time-select, #ewd-uasp-screen-background').addClass('ewd-uasp-hidden');
    });

	jQuery('#ewd-uasp-select-time-button').on('click', function() {
		//var Selected_Time = new Date(jQuery('#ewd-uasp-time-select-input-div select').val()).toLocaleString('en-ca', {timezone: ewd_uasp_php_calendar_data.timezone});
		var Selected_Time = new Date(jQuery('#ewd-uasp-time-select-input-div select').val()*1000); console.log(ewd_uasp_php_calendar_data.timezone_offset);
		jQuery('#ewd-uasp-das-service-provider').val(jQuery('#ewd-uasp-time-select').data('provider'));
		jQuery('#ewd-uasp-selected-appointment-time').val(Selected_Time.getFullYear() + "-" + ('0' + (Selected_Time.getMonth() + 1)).slice(-2) + "-" + ('0' + Selected_Time.getDate()).slice(-2) + " " + ('0' + Selected_Time.getHours()).slice(-2) + ":" + ('0' + Selected_Time.getMinutes()).slice(-2) + ":" + ('0' + Selected_Time.getSeconds()).slice(-2));
		jQuery('#ewd-uasp-time-select, #ewd-uasp-screen-background').addClass('ewd-uasp-hidden');
	});
});