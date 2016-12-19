<?php
$joined_features_waitlist = false;

$joined_waitlists = WMobilePack_Options::get_setting('joined_waitlists');

if ($joined_waitlists != '' && in_array('themes_features', $joined_waitlists))
    $joined_features_waitlist = true;

// check if we have a https connection
$is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

?>

<div class="details waitlist wmp_waitlist_container">
	<div class="waitlist-subscribe">
		<div class="spacer-10"></div>
		<p>Sign up and get FREE mobile growth lessons:</p>
		<div class="spacer-10"></div>
		<?php if ($joined_features_waitlist == false):?>
			<form id="wmp_waitlist_form" name="wmp_waitlist_form" method="post">
				<input name="wmp_waitlist_emailaddress" id="wmp_waitlist_emailaddress" type="text" placeholder="Your e-mail address" class="small" value="<?php echo get_option( 'admin_email' );?>" />
				<div class="spacer-5"></div>
				<div class="field-message error" id="error_emailaddress_container"></div>
				<div class="spacer-10"></div>

				<a class="btn blue smaller" href="javascript:void(0)" id="wmp_waitlist_send_btn">Subscribe</a>
			</form>
		<?php endif;?>
		<div id="wmp_waitlist_added" class="added" style="display: <?php echo $joined_features_waitlist ? 'block' : 'none'?>;">
			<div class="switcher blue">
				<div class="msg">SUBSCRIBED</div>
				<div class="check"></div>
			</div>
			<div class="spacer-15"></div>
		</div>
	</div>
</div>
<div class="spacer-15"></div>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            <?php if ($joined_features_waitlist == false):?>
            window.WMPJSInterface.add("UI_joinwaitlist",
                "WMP_WAITLIST",
                {
                    'DOMDoc':       window.document,
                    'container' :   window.document.getElementById('wmp_waitlist_container'),
                    'submitURL' :   '<?php echo $is_secure ? WMP_WAITLIST_PATH_HTTPS : WMP_WAITLIST_PATH;?>',
                    'listType' :    'themes_features'
                },
                window
            );

            <?php endif;?>
        });
    }
</script>
