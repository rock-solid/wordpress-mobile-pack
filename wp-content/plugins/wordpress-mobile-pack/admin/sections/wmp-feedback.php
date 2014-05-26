<?php
	// get current screen
	$screen = get_current_screen();
    
	// set current page
	if($screen->id !== '')
		if($screen->id == 'toplevel_page_wmp-options')
			$current_page = "What's new";
		else
			$current_page = str_replace('wp-mobile-pack_page_wmp-options-','',$screen->id);
	else
		$current_page = '';

?>

<div class="form-box feedback">
    <h2>Give Us Your Feedback</h2>
    <div class="spacer-10"></div>
    <p>You too can contribute to a better Mobile Web.  We're eager to hear your feedback and be sure that we ALWAYS answer it.</p>
    <div class="spacer-10"></div>
    <form id="wmp_feedback_form" name="wmp_feedback_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_send_feedback" method="post">
        <input type="hidden" name="wmp_feedback_page" id="wmp_feedback_page" value="<?php echo ucfirst($current_page);?>" />
        
        <input type="text" name="wmp_feedback_email" id="wmp_feedback_email" placeholder="Your e-mail address" class="small" />
        <div id="error_email_container" class="field-message error"></div> 
        <div class="spacer-10"></div>
        
        <input type="text" name="wmp_feedback_name" id="wmp_feedback_name" placeholder="Your name" class="small" />
        <div id="error_name_container" class="field-message error"></div> 
        <div class="spacer-10"></div>
        
        <textarea name="wmp_feedback_message" id="wmp_feedback_message" placeholder="You're awesome, did you know that?" class="small"></textarea>
        <div id="error_message_container" class="field-message error"></div>
        <div class="spacer-10"></div>
        <a class="btn green smaller" href="javascript:void(0)" id="wmp_feedback_send_btn">Send</a>
    </form>
</div>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            window.WMPJSInterface.add("UI_feedback","WMP_SEND_FEEDBACK",{'DOMDoc':window.document}, window);
        });
    }
</script>