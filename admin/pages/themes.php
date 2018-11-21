<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){

            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<?php

	$selected_theme = WMobilePack_Options::get_setting('theme');

	$arr_themes = array();

	$themeManager = new ThemeManager(new Theme());
	$themeContents = $themeManager->read();
	if (!empty($themeContents)) {
		$themeManager->setTheme($themeManager->deserialize($themeContents));
	}

	$themeFile = $themeManager->getTheme();

	if(isset($_POST['Newspaper'])) {
		$themeFile->setLayout(1);
		$success = $themeManager->write();
		if($success) {
			header("Refresh:3");
		}
	} elseif(isset($_POST['Magazine'])) {
		$themeFile->setLayout(2);
		$success = $themeManager->write();
		if($success) {
			header("Refresh:3");
		}
	}

	foreach (WMobilePack_Themes_Config::get_allowed_themes() as $i => $theme) {
		$pwa_layout_id = null;

		if($i == 3) {
			// Set to Newspaper
			$pwa_layout_id = 1;
		} else {
			// Set to Magazine
			$pwa_layout_id = 2;
		}

		$arr_themes[] = array(
			'id' => $i,
			'pwa_layout_id' => $pwa_layout_id,
			'title'=> $theme,
			'icon' => plugins_url().'/'.WMP_DOMAIN.'/admin/images/themes/theme-'.$i.'.jpg',
			'selected' => intval($themeFile->getLayout() == $pwa_layout_id)
		);
	}



?>
<div id="wmpack-admin">
	<div class="spacer-60"></div>
    <!-- set title -->
    <h1>Publisher's Toolbox PWA <?php echo WMP_VERSION;?></h1>
	<div class="spacer-20"></div>
	<div class="themes">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>
			<form method="post" enctype="multipart/form-data">
				<?php if (count($arr_themes) > 0):?>

					<div class="details theming">

						<h2 class="title">Available Mobile App Themes</h2>
						<div class="spacer-30"></div>

						<div class="themes" style="width: 220px;">
							<?php
								foreach ($arr_themes as $theme){
									require(WMP_PLUGIN_PATH.'admin/sections/theme-box.php');
								}
							?>
						</div>
						<div class="spacer-0"></div>
					</div>
					<div class="spacer-10"></div>

				<?php endif;?>
			</form>
			<div class="spacer-10"></div>
        </div>

        <div class="right-side">
        </div>
	</div>
</div>
