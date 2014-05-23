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
    <p>We're going to reply to your e-mail address <em><?php echo get_option( 'admin_email' );?></em> as soon as possible.</p>
    <div class="spacer-10"></div>
    <form id="feedback_form" name="feedback_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_send_feedback" method="post">
        <input type="hidden" name="feedback_page" id="feedback_page" value="<?php echo ucfirst($current_page);?>" />
        <textarea name="feedback_message" id="feedback_message" placeholder="Your message" class="small"></textarea>
        <div id="error_message_container" class="field-message error"></div>
        <div class="spacer-10"></div>
        <a class="btn green smaller" href="javascript:void(0)" id="feedback_send_btn">Send</a>
    </form>
</div>

<script type="text/javascript">
    if (window.JSInterface && window.JSInterface != null){
        jQuery(document).ready(function(){
            
            window.JSInterface.add("UI_feedback","SEND_FEEDBACK",{'DOMDoc':window.document}, window);
        });
    }
</script>