<a class="button-secondary" id="<?php wptouch_admin_the_setting_name(); ?>"><?php wptouch_admin_the_setting_desc(); ?></a>
<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
	<i class="wptouch-tooltip icon-info-sign" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
<?php } ?>
<?php if ( wptouch_admin_get_setting_level() == WPTOUCH_SETTING_ADVANCED ) { ?> <span class="advanced"><?php _e( 'Advanced', 'wptouch-pro' ); ?></span><?php } ?>
<?php if ( wptouch_admin_is_setting_new() ) { ?> <span class="new"><?php _e( 'New', 'wptouch-pro' ); ?></span><?php } ?>
