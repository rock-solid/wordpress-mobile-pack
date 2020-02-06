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

<div class="form-box feedback">
    <h2>Give Us Your Feedback</h2>
    <div class="spacer-10"></div>
    <p>Help us improve WP Mobile Pack. We're eager to hear your feedback and be sure that we ALWAYS answer it.</p>
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
        <?
        /*
        <p>Webcrumbz will use the information you provide on this form to be in touch with you and to provide updates and marketing. Please let us know all the ways you would like to hear from us:</p>
        <div class="spacer-10"></div>
        */
        ?>
        <input type="checkbox" name="wmp_feedback_permissions_email" id="wmp_feedback_permissions_email" value="1" style="margin-left: 0;" /> Keep me posted on special offers, updates etc.
        <div class="spacer-10"></div>
        <?
        /*
        <input type="checkbox" name="wmp_feedback_permissions_directemail" id="wmp_feedback_permissions_directemail" value="1" /> Direct Email
        <div class="spacer-10"></div>
        */
        ?>
        <a class="btn green smaller" href="javascript:void(0)" id="wmp_feedback_send_btn">Send</a>
    </form>
</div>

<div class="ask-review">
    <div class="spacer-10"></div>
    <p>If you like Wordpress Mobile Pack, <a href="https://wordpress.org/support/view/plugin-reviews/wordpress-mobile-pack?filter=5#postform" target="_blank">please leave us a &#9733;&#9733;&#9733;&#9733;&#9733; rating</a>. A huge thank you in advance!</p>
</div>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            window.WMPJSInterface.add("UI_feedback","WMP_SEND_FEEDBACK",{'DOMDoc':window.document, 'feedbackEmail': '<?php echo WMP_FEEDBACK_EMAIL;?>'}, window);
        });
    }
</script>
