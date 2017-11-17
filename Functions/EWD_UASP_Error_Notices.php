<?php
/* Add any update or error notices to the top of the admin page */
function EWD_UASP_Error_Notices(){
    global $ewd_uasp_message;
		if (isset($ewd_uasp_message)) {
			  if (isset($ewd_uasp_message['Message_Type']) and $ewd_uasp_message['Message_Type'] == "Update") {echo "<div class='updated'><p>" . $ewd_uasp_message['Message'] . "</p></div>";}
				if (isset($ewd_uasp_message['Message_Type']) and $ewd_uasp_message['Message_Type'] == "Error") {echo "<div class='error'><p>" . $ewd_uasp_message['Message'] . "</p></div>";}
		}
}

?>