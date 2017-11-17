/* Used to show and hide the admin tabs for UPCP */
function ShowTab(TabName) {
		jQuery(".OptionTab").each(function() {
				jQuery(this).addClass("HiddenTab");
				jQuery(this).removeClass("ActiveTab");
		});
		jQuery("#"+TabName).removeClass("HiddenTab");
		jQuery("#"+TabName).addClass("ActiveTab");
		
		jQuery(".nav-tab").each(function() {
				jQuery(this).removeClass("nav-tab-active");
		});
		jQuery("#"+TabName+"_Menu").addClass("nav-tab-active");
}

jQuery(document).ready(function() {
	SetReminderDeleteHandlers();
console.log(ewd_uasp_php_add_data);
	jQuery('.ewd-uasp-add-reminder').on('click', function(event) {
		var ID = jQuery(this).data('nextid'); console.log(ewd_uasp_php_add_data);

		var HTML = "<tr id='ewd-uasp-reminder-row-" + ID + "'>";
		HTML += "<td><a class='ewd-uasp-delete-reminder' data-reminderID='" + ID + "'>Delete</a></td>";
		HTML += "<td><input type='text' name='Email_Reminder_" + ID + "_Number'></td>";
		HTML += "<td><select name='Email_Reminder_" + ID + "_Unit'>";
		HTML += "<option value='Minutes'>Minute(s)</option>";
		HTML += "<option value='Hours'>Hour(s)</option>";
		HTML += "<option value='Days'>Day(s)</option>";
		HTML += "</select></td>";
		HTML += "<td><select name='Email_Reminder_" + ID + "_Email_ID'>";
		jQuery(ewd_uasp_php_add_data.emails).each(function(index, email) {HTML += "<option value='" + email.ID + "'>" + email.Name + "</option>";});
		HTML += "</select></td>";
		HTML += "<td><select name='Email_Reminder_" + ID + "_Conditional'>";
		HTML += "<option value='No'>No</option>";
		HTML += "<option value='Yes'>Yes</option>";
		HTML += "</select></td>";
		HTML += "</tr>";

		//jQuery('table > tr#ewd-uasp-add-reminder').before(HTML);
		jQuery('#ewd-uasp-email-reminders-table tr:last').before(HTML);

		ID++;
		jQuery(this).data('nextid', ID); //updates but doesn't show in DOM

		SetReminderDeleteHandlers();

		event.preventDefault();
	});
});


function ShowOptionTab(TabName) {
	jQuery(".uasp-option-set").each(function() {
		jQuery(this).addClass("uasp-hidden");
	});
	jQuery("#"+TabName).removeClass("uasp-hidden");
	
	// var activeContentHeight = jQuery("#"+TabName).innerHeight();
	// jQuery(".uasp-options-page-tabbed-content").animate({
	// 	'height':activeContentHeight
	// 	}, 500);
	// jQuery(".uasp-options-page-tabbed-content").height(activeContentHeight);

	jQuery(".options-subnav-tab").each(function() {
		jQuery(this).removeClass("options-subnav-tab-active");
	});
	jQuery("#"+TabName+"_Menu").addClass("options-subnav-tab-active");
}

function SetReminderDeleteHandlers() {
	jQuery('.ewd-uasp-delete-reminder').on('click', function(event) {
		var ID = jQuery(this).data('reminderid');
		var tr = jQuery('#ewd-uasp-reminder-row-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

jQuery(document).ready(function() {
	jQuery('#ewd-uasp-wordpress-login-option').on('change', {optionType: "wordpress"}, Update_Options);
	jQuery('#ewd-uasp-feup-login-option').on('change', {optionType: "feup"}, Update_Options);
	jQuery('#ewd-uasp-facebook-login-option').on('change', {optionType: "facebook"}, Update_Options);
	jQuery('#ewd-uasp-twitter-login-option').on('change', {optionType: "twitter"}, Update_Options);
	
	Update_Options();
});

function Update_Options(params) {
	if (params === undefined || params.data.optionType == "wordpress") {
		if (jQuery('#ewd-uasp-wordpress-login-option').is(':checked')) {
			jQuery('.ewd-uasp-wordpress-login-option').removeClass('ewd-uasp-hidden');
		}
		else {
			jQuery('.ewd-uasp-wordpress-login-option').addClass('ewd-uasp-hidden');
		}
	}
	if (params === undefined || params.data.optionType == "feup") {
		if (jQuery('#ewd-uasp-feup-login-option').is(':checked')) {
			jQuery('.ewd-uasp-feup-login-option').removeClass('ewd-uasp-hidden');
		}
		else {
			jQuery('.ewd-uasp-feup-login-option').addClass('ewd-uasp-hidden');
		}
	}
	if (params === undefined || params.data.optionType == "facebook") {
		if (jQuery('#ewd-uasp-facebook-login-option').is(':checked')) {
			jQuery('.ewd-uasp-facebook-login-option').removeClass('ewd-uasp-hidden');
		}
		else {
			jQuery('.ewd-uasp-facebook-login-option').addClass('ewd-uasp-hidden');
		}
	}
	if (params === undefined || params.data.optionType == "twitter") {
		if (jQuery('#ewd-uasp-twitter-login-option').is(':checked')) {
			jQuery('.ewd-uasp-twitter-login-option').removeClass('ewd-uasp-hidden');
		}
		else {
			jQuery('.ewd-uasp-twitter-login-option').addClass('ewd-uasp-hidden');
		}
	}
}

jQuery(document).ready(function() {
	SetMessageDeleteHandlers();

	jQuery('.ewd-uasp-add-email').on('click', function(event) {
		var Counter = jQuery(this).data('nextcounter');
		var Max_ID = jQuery(this).data('maxid');

		var HTML = "<tr id='ewd-uasp-email-message-" + Counter + "'>";
		HTML += "<td><input type='hidden' name='Email_Message_" + Counter + "_ID' value='" + Max_ID + "' /><a class='ewd-uasp-delete-message' data-messagecounter='" + Counter + "'>Delete</a></td>";
		HTML += "<td><input type='text' name='Email_Message_" + Counter + "_Name'></td>";
		HTML += "<td><input type='text' name='Email_Message_" + Counter + "_Subject'></td>";
		HTML += "<td><textarea name='Email_Message_" + Counter + "_Body'></textarea></td>";
		HTML += "</tr>";

		//jQuery('table > tr#ewd-uasp-add-reminder').before(HTML);
		jQuery('#ewd-uasp-email-messages-table tr:last').before(HTML);

		Counter++;
		Max_ID++;
		jQuery(this).data('nextcounter', Counter); //updates but doesn't show in DOM
		jQuery(this).data('maxid', Max_ID); //updates but doesn't show in DOM

		SetMessageDeleteHandlers();

		event.preventDefault();
	});
});

function SetMessageDeleteHandlers() {
	jQuery('.ewd-uasp-delete-message').on('click', function(event) {
		var ID = jQuery(this).data('messagecounter');
		var tr = jQuery('#ewd-uasp-email-message-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

jQuery(document).ready(function() {
	jQuery('.ewd-uasp-send-test-email').on('click', function() {
		jQuery('.ewd-uasp-test-email-response').remove();

		var Email_Address = jQuery('.ewd-uasp-test-email-address').val();
		var Email_To_Send = jQuery('.ewd-uasp-email-selector').val();

		if (Email_Address == "" || Email_To_Send == "") {
			jQuery('.ewd-uasp-send-test-email').after('<div class="ewd-uasp-test-email-response">Error: Select an email and enter an email address before sending.</div>');
		}

		var data = 'Email_Address=' + Email_Address + '&Email_To_Send=' + Email_To_Send + '&action=uasp_send_test_email';
        jQuery.post(ajaxurl, data, function(response) {
        	jQuery('.ewd-uasp-send-test-email').after(response);
        });
	});
});

jQuery(document).ready(function() {
	jQuery('.uasp-spectrum').spectrum({
		showInput: true,
		showInitial: true,
		preferredFormat: "hex",
		allowEmpty: true
	});

	jQuery('.uasp-spectrum').css('display', 'inline');

	jQuery('.uasp-spectrum').on('change', function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_UASP_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
		else {
			jQuery(this).css('background', 'none');
		}
	});

	jQuery('.uasp-spectrum').each(function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_UASP_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
	});
});

function EWD_UASP_hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}