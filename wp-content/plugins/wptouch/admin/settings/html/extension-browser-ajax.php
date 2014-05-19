<?php require_once( dirname( __FILE__ ) . '/../include/extension-browser.php' ); ?>

<?php wptouch_rewind_addons(); ?>

<?php while ( wptouch_has_addons( true ) ) { ?>
	<?php wptouch_the_addon(); ?>
	<?php if ( !wptouch_is_addon_active() && wptouch_is_addon_in_cloud() ) { ?>
		<?php include( 'extension-browser-item.php' ); ?>
	<?php } ?>
<?php } ?>