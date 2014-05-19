<?php require_once( WPTOUCH_DIR . '/core/settings.php' ); ?>
<?php $settings = wptouch_get_settings( 'compat' ); ?>
<?php global $wptouch_pro; ?>
<?php if ( is_array( $settings->plugin_hooks ) && count( $settings->plugin_hooks ) ) { ?>
	<ul>
	<?php foreach( $settings->plugin_hooks as $key => $value ) { ?>
		<li class="wptouch-settings">
		<label for="<?php echo $key; ?>">
			<input type="checkbox" value="<?php echo $key; ?>" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'compat', 'enabled_plugins' ); ?>[]"<?php if ( isset( $settings->enabled_plugins[ $key ] ) && $settings->enabled_plugins[ $key ] ) echo ' checked'; ?> />
			<?php $friendly_name = $wptouch_pro->get_friendly_plugin_name( $key ); ?>
			<?php echo sprintf( __( 'Enable %s', 'wptouch-pro' ), $friendly_name); ?>
			<i class="wptouch-tooltip icon-info-sign" data-original-title="<?php echo sprintf( __( 'When unchecked, %s will be disabled for users viewing your WPtouch Pro theme.', 'wptouch-pro' ), $friendly_name ); ?>"></i>
		</label>
		</li>
	<?php } ?>
		</ul>
		<input type="checkbox" value="ignore" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'compat', 'enabled_plugins' ); ?>[]" checked style="display: none;" />

<?php } else { ?>
	<p><?php _e( 'No plugins to disable.', 'wptouch-pro' ); ?></p>
<?php } ?>