<?php

$themeManager = new ThemeManager(new Theme());
$themeContents = $themeManager->read();
if (!empty($themeContents)) {
	$themeManager->setTheme($themeManager->deserialize($themeContents));
}

$theme = $themeManager->getTheme();

if (isset($_POST["save"])) {

	if (isset($_FILES['logo'])) {
		$logoUploaded = media_handle_upload('logo', 0);
		$logoUrl = wp_get_attachment_url($logoUploaded);

		if (is_wp_error($logoUploaded)) {
			$logoMsg = "There was a problem uploading the file. Please try again." . $logoUploaded->get_error_message();
		} else {
			$logoMsg = "The file has been uploaded successfully.";
		}
	}

	if (isset($_FILES['appIcon'])) {
		$appIconUploaded = media_handle_upload('appIcon', 0);
		$appIconUrl = wp_get_attachment_url($appIconUploaded);

		if (is_wp_error($appIconUploaded)) {
			$appIconMsg = "There was a problem uploading the file. Please try again. " . $appIconUploaded->get_error_message();
		} else {
			$appIconMsg = "The file has been uploaded successfully.";
		}
	}

	if (isset($_FILES['loadingSpinner'])) {
		$loadingSpinnerUploaded = media_handle_upload('loadingSpinner', 0);
		$loadingSpinnerUrl = wp_get_attachment_url($loadingSpinnerUploaded);

		if (is_wp_error($loadingSpinnerUploaded)) {
			$loadingSpinnerMsg = "There was a problem uploading the file. Please try again. " . $loadingSpinnerUploaded->get_error_message();
		} else {
			$loadingSpinnerMsg = "The file has been uploaded successfully.";
		}
	}

	$theme->setBmBurgerBarsBackground($_POST['bmBurgerBarsBackground']);
	$theme->setBmCrossBackground($_POST['bmCrossBackground']);
	$theme->setBmMenuBackground($_POST['bmMenuBackground']);
	$theme->setBmItemListColor($_POST['bmItemListColor']);
	$theme->setSelectedBackground($_POST['selectedBackground']);
	$theme->setSelectedText($_POST['selectedText']);
	$theme->setThemeColour($_POST['themeColour']);
	$theme->setBackgroundColour($_POST['backgroundColour']);

	$theme->setHeaderImage($logoUrl);
	$theme->setHamburgerImage($logoUrl);
	$theme->setLoadingSpinner(loadingSpinnerUrl);

	$themeManager->write();
}

?>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            WMPJSInterface.localpath = "<?php echo plugins_url() . "/" . WMP_DOMAIN . "/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>

<style>

.color-schemes-custom  input {
	margin: .4rem;
}

.color-schemes-custom label {
	
	text-align: right;
}

.wp-picker-holder {
	display:none;
}

.wp-color-result-text {
	display:none;
}

.headerImage, .hamburgerImage, .loadingSpinner {
	min-width: 250px;
	min-height: 36px;
}

.save {
	background: #0c4b7f;
    color: #ffffff;
    border: 1px solid #7ea82f;
    border-radius: 3px;
    padding: 7px 15px 7px 15px;
    min-width: 120px;
}

.button.wp-color-result {
	min-width: 255px;
	min-height: 36px;
}

</style>

<div id="wmpack-admin">
	<div class="spacer-60"></div>
	<!-- set title -->
	<h1>Publisher's Toolbox PWA <?php echo WMP_VERSION; ?></h1>
	<div class="spacer-20"></div>
	<div class="look-and-feel">
		<div class="left-side">
			 <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH . 'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>
            <!-- add content form -->
			<?php
		$theme_settings = WMobilePack_Themes_Config::get_theme_config();

		if ($theme_settings !== false) : ?>

				<div class="details">
					<p class="section-header">Select Colour Scheme</p>
						<div class="spacer-20"></div>
							<form method="post" id="color-settings" enctype="multipart/form-data">
								<div class="holder">
									<label>Mobile Menu Bar Background Colour</label>
									<input value="<?= $theme->getBmBurgerBarsBackground() ?>" class="bmBurgerBarsBackground" type="text" name="bmBurgerBarsBackground" id="bmBurgerBarsBackground" placeholder="Enter hex value" onkeyup="changeColour(this.className); saveValue(this)" />
									<div class="changedElement" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour(className){
											document.getElementsByClassName("changedElement")[0].style.background = document.getElementsByClassName("bmBurgerBarsBackground")[0].value;
										}
										changeColour('bmBurgerBarsBackground');
									</script>
									<script>
										var input = document.getElementById('bmBurgerBarsBackground');
										input.addEventListener("keyup", function() {
											changeColour(input.className);
											saveValue(input.id);
										})
									</script>	
								</div>	
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="bmCrossBackground">Close Button Background Colour</label>
									<input value="<?= $theme->getBmCrossBackground() ?>"   type="text" class="bmCrossBackground" name="bmCrossBackground" id="bmCrossBackground" placeholder="Enter hex value" onkeyup="changeColour2(this.className);" />
									<div class="changedElement2" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
										<script>
										function changeColour2(className){
											document.getElementsByClassName("changedElement2")[0].style.background = document.getElementsByClassName("bmCrossBackground")[0].value;
										}
										changeColour2('bmCrossBackground');
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="bmMenuBackground">Mobile Menu Background Colour</label>
									<input value="<?= $theme->getBmMenuBackground() ?>" class="bmMenuBackground" type="text" name="bmMenuBackground" placeholder="Enter hex value" onkeyup="changeColour3(this.className)" /><div class="changedElement3" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour3(className){
											document.getElementsByClassName("changedElement3")[0].style.background = document.getElementsByClassName("bmMenuBackground")[0].value;
										}
										changeColour3('bmMenuBackground');
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="bmItemListColor">Mobile Menu Item List Colour</label>
									<input value="<?= $theme->getBmItemListColor() ?>" class="bmItemListColor" type="text" name="bmItemListColor" placeholder="Enter hex value" onkeyup="changeColour4(this.className)" />
									<div class="changedElement4" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour4(className){
											document.getElementsByClassName("changedElement4")[0].style.background = document.getElementsByClassName("bmItemListColor")[0].value;
										}
										changeColour4('bmItemListColor');
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="selectedBackground">Selected Item Background Colour</label>
									<input value="<?= $theme->getSelectedBackground() ?>" class="selectedBackground" type="text" name="selectedBackground" placeholder="Enter hex value" onkeyup="changeColour5(this.className)" />
									<div class="changedElement5" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour5(className){
											document.getElementsByClassName("changedElement5")[0].style.background = document.getElementsByClassName("selectedBackground")[0].value;
										}
										changeColour5('selectedBackground');
									</script>
								</div>			
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="selectedText">Selected Text Background Color</label>
									<input value="<?= $theme->getSelectedText() ?>" class="selectedText" type="text" name="selectedText" placeholder="Enter hex value" onkeyup="changeColour6(this.className)" />
									<div class="changedElement6" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour6(className){
											document.getElementsByClassName("changedElement6")[0].style.background = document.getElementsByClassName("selectedText")[0].value;
										}
										changeColour6('selectedText');
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="themeColour">Theme Colour</label>
									<input value="<?= $theme->getThemeColour() ?>" class="themeColour" type="text" name="themeColour" placeholder="Enter hex value" onkeyup="changeColour7(this.className)" />
									<div class="changedElement7" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour7(className){
											document.getElementsByClassName("changedElement7")[0].style.background = document.getElementsByClassName("themeColour")[0].value;
										}
										changeColour7('themeColour');
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="backgroundColour">Background Colour</label>
									<input  value="<?= $theme->getBackgroundColour() ?>" class="backgroundColour" type="text" name="backgroundColour" placeholder="Enter hex value" onkeyup="changeColour8(this.className)" />
									<div class="changedElement8" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour8(className){
											document.getElementsByClassName("changedElement8")[0].style.background = document.getElementsByClassName("backgroundColour")[0].value;
										}
										changeColour8('backgroundColour');
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<img src="<?= $theme->getHeaderImage() ?>" />
									<label for="logo">App Logo</label>
									<input class="logo" type="file" name="logo" id="logo"/>
									<?= $logoMsg ?>
								</div>
								<div class="spacer-15"></div>	
								<div class="holder">
									<img src="<?= $theme->getHeaderImage() ?>" />
									<label for="appIcon">App Icon</label>
									<input class="appIcon" type="file" name="appIcon" id="appIcon"/>
									<?= $appIconMsg ?>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<img src="<?= $theme->getLoadingSpinner() ?>" />
									<label for="loadingSpinner">Loading Spinner Image</label>
									<input class="loadingSpinner" type="file" name="loadingSpinner" id="loadingSpinner"/>
									<?= $loadingSpinnerMsg ?>
								</div>
						<div class="spacer-20"></div>
						<div class="submit"><input type="submit" name="save" class="save" value="Save"/></div>		
						</form>			
				</div>
				<div class="spacer-15"></div>
            <?php endif; ?>
 
        </div>

        <div class="right-side">
            <!-- waitlist form -->
            <?php #include_once(WMP_PLUGIN_PATH.'admin/sections/waitlist.php'); ?>

            <!-- add feedback form -->
            <?php #include_once(WMP_PLUGIN_PATH.'admin/sections/feedback.php'); ?>
        </div>
	</div>
</div>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){

            window.WMPJSInterface.add("UI_customizetheme","WMP_EDIT_THEME",{'DOMDoc':window.document}, window);
            window.WMPJSInterface.add("UI_editimages","WMP_EDIT_IMAGES",{'DOMDoc':window.document}, window);
			window.WMPJSInterface.add("UI_editcover","WMP_EDIT_COVER",{'DOMDoc':window.document}, window);
			window.WMPJSInterface.add("UI_service_worker","WMP_SERVICE_WORKER",{'DOMDoc':window.document}, window);

        });
    }
</script>
