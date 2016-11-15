<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){

            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<?php
	$arr_themes = array(
		array(
			'title'=> 'Obliq',
			'icon' => plugins_url().'/'.WMP_DOMAIN.'/admin/images/theme-obliq.jpg',
			'selected' => 1,
			'bundle' => 1
		)
	);

	$upgrade_content = WMobilePack_Admin::more_updates();

	// get themes from the upgrade json
	$arr_themes = array_merge($arr_themes, WMobilePack_Admin::upgrade_pro_themes($upgrade_content));

	// filter bundled themes
	function is_bundled($item){
		return isset($item['bundle']) && $item['bundle'] == 1;
	}

	$arr_bundled_themes = array_filter($arr_themes, 'is_bundled');

	// filter preorder themes
	function is_preorder($item){
		return isset($item['preorder']) && $item['preorder'] == 1;
	}

	$arr_preorder_themes = array_filter($arr_themes, 'is_preorder');
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

			<?php if (count($arr_preorder_themes) > 0):?>

				<div class="details theming">

					<?php if (isset($upgrade_content['premium']['preorder']['title'])):?>
						<h2 class="title"><?php echo $upgrade_content['premium']['preorder']['title']; ?></h2>
						<div class="spacer-30"></div>
					<?php endif;?>

					<div class="themes">
						<?php
							foreach ($arr_preorder_themes as $theme){
								require(WMP_PLUGIN_PATH.'admin/sections/theme-box.php');
							}
						?>
					</div>
					<div class="spacer-10"></div>

					<?php
						if (isset($upgrade_content['premium']['preorder']['notice'])){
							echo $upgrade_content['premium']['preorder']['notice'];
						}
					?>

					<div class="spacer-10"></div>
				</div>
				<div class="spacer-10"></div>
			<?php endif;?>

			<?php if (count($arr_bundled_themes) > 0):?>

				<div class="details theming">
					<?php if (isset($upgrade_content['premium']['bundle']['title'])):?>
						<h2 class="title"><?php echo $upgrade_content['premium']['bundle']['title']; ?></h2>
					<?php endif;?>

					<div class="spacer_15"></div>
					<div class="spacer-15"></div>
					<div class="themes">
						<?php
							foreach ($arr_bundled_themes as $theme){
								require(WMP_PLUGIN_PATH.'admin/sections/theme-box.php');
							}
						?>
					</div>

					<?php
						if (isset($upgrade_content['premium']['bundle']['buy']['text']) &&
							isset($upgrade_content['premium']['bundle']['buy']['link']) &&
							filter_var($upgrade_content['premium']['bundle']['buy']['link'], FILTER_VALIDATE_URL)):
					?>
						<a href="<?php echo esc_attr($upgrade_content['premium']['bundle']['buy']['link']);?>" class="btn turquoise" target="_blank" style="margin: 0 auto;">
							<span class="shopping"></span>&nbsp;
							<?php echo esc_attr($upgrade_content['premium']['bundle']['buy']['text']);?>
						</a>
					<?php endif;?>
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
            window.WMPJSInterface.add("UI_previewthemesgallery","WMP_THEMES_GALLERY",{'DOMDoc':window.document}, window);
        });
    }
</script>
