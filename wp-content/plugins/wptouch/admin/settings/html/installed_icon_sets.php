<?php if ( is_writable( WPTOUCH_CUSTOM_SET_DIRECTORY ) ) { ?>
	<div id="manage-icon-sets"><?php _e( 'Gathering information about available icon sets...', 'wptouch-pro' ); ?></div>
<?php } else { ?>
	<div id="manage-icon-set-error"><?php echo sprintf( __( 'The %s%s%s directory is not currently writable. %sPlease fix this issue to enable installation of additional icon sets.', 'wptouch-pro' ), '<strong>', '/wptouch-data/icons', '</strong>', '<br/>' ); ?></div>
<?php } ?>