<div class="<?php wptouch_admin_the_setting_name(); ?>_wrap uploader" id="<?php wptouch_admin_the_setting_name(); ?>">
	<div class="image-placeholder">
		<?php $image = wptouch_admin_get_setting_value(); ?>
		<?php if ( $image ) { ?>
		<img src="<?php echo WPTOUCH_BASE_CONTENT_URL . $image; ?>" />
		<?php } else { ?>
		<span class="spinner" style="display:none"></span>
		<?php } ?>
	</div>

	<button id="<?php wptouch_admin_the_setting_name(); ?>_upload" data-esn="<?php wptouch_admin_the_encoded_setting_name(); ?>" class="upload button-secondary"><?php _e( 'Upload', 'wptouch-pro' ); ?></button>

	<button class="delete button-secondary" <?php if ( !$image ) { echo 'style="display: none;"'; } ?>><?php _e( 'Delete', 'wptouch-pro' ); ?></button>

	<div class="progress progress-striped progress-success" style="display: none;" title="<?php _e( 'Upload Complete!', 'wptouch-pro' ); ?>" rel="popover" data-placement="right">
	  <div class="bar" style="width: 20%;"></div>
	</div>

	<br class="clearfix" />
	<span class="upload-desc"><?php wptouch_admin_the_setting_desc(); ?></span>
	<div id="<?php wptouch_admin_the_setting_name(); ?>_spot" class="<?php wptouch_admin_the_setting_name(); ?>_upload" style="display: none;"></div>
</div>
