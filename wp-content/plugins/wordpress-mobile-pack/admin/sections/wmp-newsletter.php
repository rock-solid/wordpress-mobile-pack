<?php
	// get current screen
	$screen = get_current_screen();
    
	// set current page
	if ($screen->id !== '')
		if ($screen->id == 'toplevel_page_wmp-options')
			$current_page = "What's new";
		else
			$current_page = str_replace('wp-mobile-pack_page_wmp-options-','',$screen->id); 
	else
		$current_page = ''; 
?>


<div class="form-box wmp-newsletter">
    <h2>Join Our Newsletter</h2>
    <div class="spacer-10"></div>
    <p>Receive monthly freebies, Special Offers &amp; Access to Exclusive Subscriber Content.</p>
    <div class="spacer-10"></div>
    <form id="wmp_newsletter_form" name="wmp_newsletter_form" action="" method="post">
        <input type="hidden" name="wmp_newsletter_page" id="wmp_newsletter_page" value="<?php echo ucfirst($current_page);?>" />
        <input type="text" name="wmp_newsletter_email" id="wmp_newsletter_email" placeholder="Your e-mail" class="small" />
        <div id="error_email_container" class="field-message error"></div> 
        <div class="spacer-10"></div>
        <a class="btn green smaller" href="javascript:void(0)" id="wmp_newsletter_send_btn">Subscribe</a>
    </form>
</div>


<script type="text/javascript">
    if (window.JSInterface && window.JSInterface != null){
        jQuery(document).ready(function(){
            
            window.JSInterface.add("UI_wmp_newsletter","WMP_NEWSLETTER",{'DOMDoc':window.document,'submitURL' : '<?php echo WMP_NEWSLETTER_PATH;?>'}, window);
        });
    }
</script>