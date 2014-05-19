<?php $settings = wptouch_get_settings( 'bncid' ); ?>

<script type="text/javascript">
	var bncHasLicense = 0;
</script>

<div id="license-settings-area">
	<div id="license-area-left">
		<h3><?php _e( 'License Details', 'wptouch-pro' ); ?></h3>

		<p><strong><?php _e( 'Note', 'wptouch-pro' ); ?>:</strong><br />
		<?php _e( 'Once you activate, this page will be hidden.', 'wptouch-pro' ); ?>
		</p>

		<p><?php _e( 'It will only reappear if you reset your WPtouch Pro settings.', 'wptouch-pro' ); ?></p>
	</div>
	<div id="license-area-right">
		<input type="text" id="license_email" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'bncid', 'bncid' ); ?>" value="<?php if ( $settings->bncid ) echo $settings->bncid; else _e( 'Account E-Mail Address', 'wptouch-pro' ); ?>" data-start="<?php _e( 'Account E-Mail Address', 'wptouch-pro'  ); ?>" onfocus="if ( jQuery( '#license_email' ).val() == jQuery( '#license_email' ).attr( 'data-start' ) ) { this.value='' };" onblur="if ( jQuery( '#license_email' ).val() == '' ) this.value = jQuery( '#license_email' ).attr( 'data-start' );" />

		<input type="text" placeholder="<?php _e( 'Product License Key', 'wptouch-pro' ); ?>" id="license_key" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'bncid', 'bncid' ); ?>" value="<?php if ( $settings->wptouch_license_key ) echo $settings->wptouch_license_key; else _e( 'Product License Key', 'wptouch-pro' ); ?>" data-start="<?php _e( 'Product License Key', 'wptouch-pro' ); ?>" onfocus="if ( jQuery( '#license_key' ).val() == jQuery( '#license_key' ).attr( 'data-start' ) ) { this.value='' };" onblur="if ( jQuery( '#license_key' ).val() == '' ) this.value = jQuery( '#license_key' ).attr( 'data-start' );" />

		<div id="activate-license">
			<a href="#" class="button"><?php _e( 'Activate', 'wptouch-pro' ); ?></a>
		</div>

		<div id="progress-license" class="license-status">
			<div class="progress progress-striped active">
			  <div class="bar" style="width: 20%;"></div>
			</div>
		</div>

		<div id="success-license" class="license-status">
			<?php _e( 'Success.', 'wptouch-pro' ); ?>
			<p><?php _e( 'Activation Complete. Enjoy WPtouch Pro!', 'wptouch-pro' ); ?></p>
		</div>

		<div id="rejected-license" class="license-status">
			<?php _e( 'E-mail address or license key rejected', 'wptouch-pro' ); ?>
			<p><?php _e( 'The wptouch.com server rejected your E-Mail address and/or License Key. Please check they are correct and try again.', 'wptouch-pro' ); ?></p>
		</div>

		<div id="too-many-license" class="license-status">
			<?php _e( 'No licenses remaining.', 'wptouch-pro' ); ?>
			<p><?php _e( 'You have used all your activations. What do you want to do?', 'wptouch-pro' ); ?></p>
			<a href="http://www.wptouch.com/pricing/" class="button"><?php _e( 'Upgrade My License', 'wptouch-pro' ); ?></a>
			<a href="http://www.wptouch.com/support/" class="button"><?php _e( 'Contact support for assistance', 'wptouch-pro' ); ?></a>
		</div>

		<div id="server-issue-license" class="license-status">
			<?php _e( 'The server is unavailable.', 'wptouch-pro' ); ?>
			<p><?php _e( 'The wptouch.com server currently cannot authorize your License.', 'wptouch-pro' ); ?></p>
			<p><?php echo sprintf( __( 'Please %scontact us%s and let us know about it.', 'wptouch-pro' ), '<a href="http://www.wptouch.com/support/">', '</a>' ); ?></p>
		</div>
	</div>
</div>
