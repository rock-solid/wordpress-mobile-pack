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

	foreach (WMobilePack_Themes_Config::get_allowed_themes() as $i => $theme){

		$arr_themes[] = array(
			'id' => $i,
			'title'=> $theme,
			'icon' => plugins_url().'/'.WMP_DOMAIN.'/admin/images/themes/theme-'.$i.'.jpg',
			'selected' => intval($selected_theme == $i)
		);
	}

	$upgrade_content = WMobilePack_Admin::more_updates();

	// get themes from the upgrade json
	$arr_pro_themes = WMobilePack_Admin::upgrade_pro_themes($upgrade_content);
?>
<div id="wmpack-admin">
	<div class="spacer-60"></div>
    <!-- set title -->
    <h1><?php echo WMP_PLUGIN_NAME.' '.WMP_VERSION;?></h1>
	<div class="spacer-20"></div>
	<div class="themes">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>

			<?php if (count($arr_themes) > 0):?>

				<div class="details theming">

					<h2 class="title">Available Mobile App Themes</h2>
					<div class="spacer-30"></div>

					<?php if (isset($upgrade_content['premium']['themes']['message'])):?>
						<p><?php echo $upgrade_content['premium']['themes']['message'];?></p>
						<div class="spacer-30"></div>
					<?php endif;?>

					<div class="themes" style="width: 450px;">
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

			<?php if (count($arr_pro_themes) > 0):?>

				<div class="details theming">
					<div class="ribbon relative">
						<div class="starred"></div>
					</div>

					<?php if (isset($upgrade_content['premium']['themes']['title'])):?>
						<h2 class="title"><?php echo $upgrade_content['premium']['themes']['title']; ?></h2>
					<?php else: ?>
						<h2 class="title">Premium Mobile App Themes</h2>
					<?php endif;?>

					<div class="spacer-30"></div>
					<div class="themes">
						<?php
							foreach ($arr_pro_themes as $theme){
								require(WMP_PLUGIN_PATH.'admin/sections/theme-box.php');
							}
						?>
					</div>
				</div>
			<?php endif;?>
			<div class="spacer-10"></div>
        </div>

        <div class="right-side">
            <!-- waitlist form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/waitlist.php'); ?>

            <!-- add feedback form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/feedback.php'); ?>
        </div>
	</div>
</div>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            window.WMPJSInterface.add("UI_switchtheme","WMP_SWITCH_THEME",{'DOMDoc':window.document, 'selectedTheme': <?php echo $selected_theme?>}, window);
        });
    }
</script>
