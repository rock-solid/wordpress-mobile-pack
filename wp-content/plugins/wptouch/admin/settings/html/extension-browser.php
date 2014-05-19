<?php if ( !wptouch_can_cloud_install( false ) ) { ?>
	<div class="cloud-update-issue"><i class="icon-cloud"></i> <?php echo sprintf( __( 'Your server configuration is preventing WPtouch Pro from installing and updating from the Cloud. %sPlease visit %sthis article%s to follow the steps to enable Cloud install, or you can manually download and install into the wptouch-data/%s directory.', 'wptouch-pro' ), '<br />', '<a href="http://www.wptouch.com/support/knowledgebase/themes-or-extensions-cannot-be-downloaded/">', '</a>', 'extensions' ); ?></div>
<?php } ?>
<ul class="cloud-browser">
	<?php while ( wptouch_has_addons( true ) ) { ?>
		<?php wptouch_the_addon(); ?>
			<?php if ( !wptouch_is_addon_in_cloud() ) { ?>
				<?php include( 'extension-browser-item.php' ); ?>
			<?php } ?>
	<?php } ?>

	<li id="wptouch-addon-browser-load-ajax">
		<div class="load"><span class="text"><?php _e( 'Loading Cloud Extensions', 'wptouch-pro' ); ?>&hellip;</span></div>
	</li>
</ul>