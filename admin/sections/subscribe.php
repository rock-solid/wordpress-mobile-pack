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
		<!-- Begin Mailchimp Signup Form -->
		<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
		<style type="text/css">
			#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
			#mc_embed_signup .clear {width:96%;}
			#mc_embed_signup h2 {width:96%;}
			/* Add your own Mailchimp form style overrides in your site stylesheet or in this style block.
				We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
		</style>
		<div id="mc_embed_signup">
			<form action="https://wpmobilepack.us13.list-manage.com/subscribe/post?u=df15f7f3ba7071146f98e66b3&amp;id=6989f14b64" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup_scroll">
					<h2>Subscribe</h2>
					<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
					<div class="mc-field-group">
						<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
						</label>
						<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" style="margin:auto;">
					</div>
					<div class="mc-field-group">
						<label for="mce-FNAME">First Name </label>
						<input type="text" value="" name="FNAME" class="" id="mce-FNAME" style="margin:auto;">
					</div>
					<div class="mc-field-group">
						<label for="mce-LNAME">Last Name </label>
						<input type="text" value="" name="LNAME" class="" id="mce-LNAME" style="margin:auto;">
					</div>
					<div class="mc-field-group">
						<label for="mce-MMERGE5">Business </label>
						<input type="text" value="" name="MMERGE5" class="" id="mce-MMERGE5" style="margin:auto;">
					</div>
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_df15f7f3ba7071146f98e66b3_6989f14b64" tabindex="-1" value=""></div>
					<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
				</div>
			</form>
		</div>
		<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';fnames[5]='MMERGE5';ftypes[5]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
		<!--End mc_embed_signup-->
	</div>
</div>
<div class="spacer-15"></div>
