<input type="checkbox" class="checkbox" name="<?php wptouch_admin_the_encoded_setting_name(); ?>" id="<?php wptouch_admin_the_setting_name(); ?>"<?php if ( wptouch_admin_is_setting_checked() ) echo " checked"; ?> />
<label class="checkbox" for="<?php wptouch_admin_the_setting_name(); ?>"><?php wptouch_admin_the_setting_desc(); ?></label>
<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
	<i class="wptouch-tooltip icon-info-sign" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
<?php } ?>
<?php if ( wptouch_admin_get_setting_level() == WPTOUCH_SETTING_ADVANCED ) { ?> <span class="advanced"><?php _e( 'Advanced', 'wptouch-pro' ); ?></span><?php } ?>
<?php if ( wptouch_admin_is_setting_new() ) { ?> <span class="new"><?php _e( 'New', 'wptouch-pro' ); ?></span><?php } ?>
<input type="hidden" name="hid-<?php wptouch_admin_the_encoded_setting_name(); ?>" />