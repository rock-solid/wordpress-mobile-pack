<?php
	$debug_data = array();
	if ( isset( $_SERVER[ 'SERVER_SOFTWARE' ] ) ) $debug_data[] = $_SERVER[ 'SERVER_SOFTWARE' ];

	$extensions_to_check = array( 'gd', 'curl', 'exif', 'mcrypt', 'openssl', 'json',' mbstring', 'openssl' );

	$debug_data[] = 'PHP ' . phpversion() . ' (' . implode( ', ', array_intersect( $extensions_to_check, get_loaded_extensions() ) ) . ')';
?>

<?php $settings = wptouch_get_settings(); ?>
<?php if ( $settings->debug_log ) { ?>
	<strong><a class="button" href="<?php echo ( WPTOUCH_DEBUG_URL . '/' . wptouch_debug_get_filename() ); ?>" target="_blank"><?php _e( 'View Debug File', 'wptouch-pro' ); ?></a></strong>
<br /><br />
<?php } ?>
<?php if ( wptouch_get_locale() == 'fr_FR' ) { ?>
<strong><?php _e( 'Your Server Configuration', 'wptouch-pro' ); ?> :</strong>
<?php } else { ?>
<strong><?php _e( 'Your Server Configuration', 'wptouch-pro' ); ?>:</strong>
<?php } ?>
<br />
<?php echo implode( ', ', $debug_data );