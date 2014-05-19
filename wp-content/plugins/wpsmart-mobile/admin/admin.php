<?php function wps_admin_main() { global $wpsmart; ?>

<div class="wps-admin-wrap">

	<div class="wps-admin-header">
		<img src="<?php echo $wpsmart->wps_plugin_admin_uri() ?>/images/wpsmart.png"/>
	</div>
	
	<div class="wps-admin-save-bar">
		<span class="wps-loader"><img src="<?php echo $wpsmart->wps_plugin_admin_uri() ?>/images/loader.gif"/></span>
		<span class="wps-preview-link"><a href="#">Preview</a></span>
		<button class="wps-save wps-save-form">Save All Changes</button>
	</div>
	
	<div class="wps-admin-content-wrap">
		
		<form action="#" name="wps_admin_update_form" id="wps_admin_update_form" method="post" enctype="multipart/form-data">
		
			<?php include_once('wps-admin-nav.php'); ?>
		
			<div class="wps-admin-content">
								
				<?php include_once('wps-admin-settings.php'); ?>
				<?php include_once('wps-admin-themes.php'); ?>
				<?php include_once('wps-admin-appearance.php'); ?>
				<?php include_once('wps-admin-menu.php'); ?>
				
				<div class="wps-admin-saved" id="wps_admin_saved"><p>All changes saved!</p></div>
			</div>
		
		</form>
		
	</div>
	
	<div class="wps-admin-save-bar">
		<span class="wps-loader"><img src="<?php echo $wpsmart->wps_plugin_admin_uri() ?>/images/loader.gif"/></span>
		<span class="wps-preview-link"><a href="#">Preview</a></span>
		<button class="wps-save wps-save-form">Save All Changes</button>
	</div>
	
	<div class="wps-admin-footer">Handcrafted by <a href="http://www.wpsmart.com" target="_blank">WPSmart</a><span class="wps-version">Version <?php echo WPSMART_VERSION ?></div>
	
	<?php include_once('wps-admin-preview.php'); ?>
	
</div>

<?php }