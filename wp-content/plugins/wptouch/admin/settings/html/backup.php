<button class="button-secondary" name="<?php wptouch_admin_the_encoded_setting_name(); ?>" id="backup"><?php _e( 'Download Backup File', 'wptouch-pro' ); ?></button>
<button class="button-secondary" name="<?php wptouch_admin_the_encoded_setting_name(); ?>" id="restore"><?php _e( 'Restore Backup File', 'wptouch-pro' ); ?></button>
<div id="restore_uploader" style="display: none;"></div>
<input type="hidden" name="hid-<?php wptouch_admin_the_encoded_setting_name(); ?>" />