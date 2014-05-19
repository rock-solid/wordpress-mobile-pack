<label for="latest_posts_page"><?php _e( "Custom latest posts page", "wptouch-pro" ); ?></label>

<?php if ( wptouch_admin_get_setting_level() == WPTOUCH_SETTING_ADVANCED ) { ?>
	<span class="advanced"><?php _e( 'Advanced', 'wptouch-pro' ); ?></span>
<?php } ?>

<?php if ( wptouch_admin_is_setting_new() ) { ?>
	<span class="new"><?php _e( 'New', 'wptouch-pro' ); ?></span>
<?php } ?>