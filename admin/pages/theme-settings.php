<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#save").click(function(e) {
		var jsonData1 = {};
		
		var fieldsetData = jQuery("#color-settings").serializeArray();
		var _this = this;
		jQuery.each(fieldsetData, function() {
			
			var custKey = jQuery('input[name="' + this.name + '"')[0].classList[0];
			// var deconc = this.name
			// console.log(_this.getAttribute('class'));
			jsonData1[custKey] = this.value || '';
		});
		 console.log(jsonData1);
         var output1 = JSON.stringify(jQuery("#color-settings").serializeArray());
		jQuery.ajax(
		{

			url : <?php echo '"'. home_url() .'"'; ?>,
			type: "POST",
			data: output1,
			success: function(response) {
				alert("Settings saved.");
			}
		}); 
		e.preventDefault();
	});	
});
</script>	<!--must be used as a fallback in case the php doesn't work-->

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){

            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
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
	background: #9aca40;
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
    <h1>Publisher's Toolbox PWA <?php echo WMP_VERSION;?></h1>
	<div class="spacer-20"></div>
	<div class="look-and-feel">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>

            <!-- add content form -->

			<?php
                $theme_settings = WMobilePack_Themes_Config::get_theme_config();

                if ($theme_settings !== false):
            ?>

				<div class="details">
					<h2 class="title">Customize Color Schemes and Fonts</h2>
					<div class="spacer-15"></div>
					<div class="grey-line"></div>
					<div class="spacer-30"></div>

					<?php if (version_compare(PHP_VERSION, '5.3') < 0) :?>
						<div class="message-container warning">
							<div class="wrapper">
								<span>Customizing the theme's colors and fonts requires PHP5.3 or greater. Your PHP version (<?php echo PHP_VERSION;?>) is not supported.</span>
							</div>
						</div>
						<div class="spacer-20"></div>
					<?php endif;?>

					<form name="wmp_edittheme_form" id="wmp_edittheme_form" action="" method="post">
						<div class="color-schemes" style="display:none;">
							<p class="section-header">Select Colour Scheme</p>
							<div class="spacer-20"></div>

							<!-- add labels -->
							<div class="colors description">
								<?php foreach ($theme_settings['labels'] as $key => $description):?>
									<div class="color-" title="<?php echo $description;?>"><?php echo $key+1;?></div>
								<?php endforeach; ?>
							</div>
							<div class="spacer-15"></div>

							<!-- add presets radio buttons & colors -->
							<?php
								$selected_color_scheme = WMobilePack_Options::get_setting('color_scheme');
								if ($selected_color_scheme == '')
									$selected_color_scheme = 1;

								foreach ($theme_settings['presets'] as $color_scheme => $default_colors):
							?>
								<input type="radio" name="wmp_edittheme_colorscheme" id="wmp_edittheme_colorscheme" value="<?php echo $color_scheme;?>" <?php if ($color_scheme == $selected_color_scheme) echo 'checked="checked"';?> autocomplete="off" />
								<div class="colors">

									<?php foreach ($theme_settings['labels'] as $key => $description):?>
										<div class="color-<?php echo $color_scheme.'-'.$key;?>" title="<?php echo $description;?>" style="background: <?php echo $theme_settings['presets'][$color_scheme][$key];?>"></div>
									<?php endforeach;?>

								</div>
								<div class="spacer-20"></div>
							<?php endforeach;?>

							<!-- add custom scheme radio button
							<input type="radio" name="wmp_edittheme_colorscheme" id="wmp_edittheme_colorscheme" value="0" <?php echo $selected_color_scheme == 0 ? 'checked="checked"' : '';?> autocomplete="off" />
							<p>Edit custom colors</p>-->
						</div>

						<!-- start notice -->
						<div class="notice notice-left left" style="width: 50%; display:none;">
							<span>
								The color scheme will impact the following sections within the mobile web application:<br/><br/>
								<?php
									foreach ($theme_settings['labels'] as $key => $description)
										echo ($key+1).'.&nbsp;'.$description.'<br/>';
								?>
							</span>
						</div>

						<div class="spacer-20"></div>

						<!-- start color pickers -->
						<!--<div class="color-schemes-custom" style="display: <?php echo $selected_color_scheme == 0 ? 'block' : 'none';?>;">-->
						<div class="color-schemes-custom" style="display:block;">	
							<p class="section-header">Select Colour Scheme</p>
							<p style="font-size: 8px;">To choose a colour, click the swatch and enter its hexcode</p> 
							<div class="spacer-20"></div>
							<fieldset id="color-settings">
								<div class="holder">
									<label>Mobile Menu Bar Background Colour</label><input class="bmBurgerBarsBackground" type="text" name="wmp_edittheme_customcolor1-bmBurgerBarsBackground" id="bmBurgerBarsBackground" value="<?php echo $color_value;?>" autocomplete="off" />
								</div>	
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="bmCrossBackground">Close Button Background Colour</label>
									<input class="bmCrossBackground" type="text" name="wmp_edittheme_customcolor2" id="wmp_edittheme_customcolor2" value="<?php echo $color_value;?>" autocomplete="off" />
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="bmMenuBackground">Mobile Menu Background Colour</label>
									<input class="bmMenuBackground" type="text" name="wmp_edittheme_customcolor3" id="wmp_edittheme_customcolor3" value="<?php echo $color_value;?>" autocomplete="off" />
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="bmItemListColor">Mobile Menu Item List Colour</label>
									<input class="bmItemListColor" type="text" name="wmp_edittheme_customcolor4" id="wmp_edittheme_customcolor4" value="<?php echo $color_value;?>" autocomplete="off" />
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="SelectedBackground">Selected Item Background Colour</label>
									<input class="selectedBackground" type="text" name="wmp_edittheme_customcolor5" id="wmp_edittheme_customcolor5" value="<?php echo $color_value;?>" autocomplete="off" />
								</div>			
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="selectedText">Selected Text Background Color</label>
									<input class="selectedText" type="text" name="wmp_edittheme_customcolor6" id="wmp_edittheme_customcolor6" value="<?php echo $color_value;?>" autocomplete="off" />
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="themeColour">Theme Colour</label>
									<input class="themeColour" type="text" name="wmp_edittheme_customcolor7" id="wmp_edittheme_customcolor7" value="<?php echo $color_value;?>" autocomplete="off" />
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="backgroundColour">Background Colour</label>
									<input class="backgroundColour" type="text" name="wmp_edittheme_customcolor8" id="wmp_edittheme_customcolor8" value="<?php echo $color_value;?>" autocomplete="off" />
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="headerImage">Header Image (Paste URL)</label>
									<input class="headerImage" type="url" name="headerImage" id="headerImage" placeholder="www.example.com/header-image.png"/>
								</div>
								<div class="spacer-15"></div>	
								<div class="holder">
									<label for="hamburgerImage">Mobile Menu Bar Image (Paste URL)</label>
									<input class="hamburgerImage" type="url" name="hamburgerImage" id="hamburgerImage" placeholder="www.example.com/mobile-menu-image.png"/>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="loadingSpinner">Loading Spinner Image (Paste URL)</label>
									<input class="loadingSpinner" type="url" name="loadingSpinner" id="loadingSpinner" placeholder="www.example.com/spinner.gif"/>
								</div>
						<div class="spacer-20"></div>
						<div class="submit"><input type="button" id="save" class="save" value="Save"/></div>				
							</fieldset>	
							</div>
					</form>
				</div>
				<div class="spacer-15"></div>
            <?php endif;?>

            <div class="details branding">

                <h2 class="title">Customize Your App's Logo and Icon</h2>
                <div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-20"></div>
                <p>You can also personalize your app by adding <strong>your own logo and icon</strong>. The logo will be displayed on the home page of your mobile web app, while the icon will be used when readers add your app to their homescreen.</p>
                <div class="spacer-20"></div>

				<?php
					$warning_message = '';
					$icon_filename = WMobilePack_Options::get_setting('icon');

					if ($icon_filename == '') {
						$warning_message = 'Upload an App Icon to take advantage of the Add To Home Screen functionality!';

					} elseif ($icon_filename != '' && file_exists(WMP_FILES_UPLOADS_DIR . $icon_filename)) {
						foreach (WMobilePack_Uploads::$manifest_sizes as $manifest_size) {
							if (!file_exists(WMP_FILES_UPLOADS_DIR . $manifest_size . $icon_filename)) {
								$warning_message = 'WP Mobile Pack Version 3.2+ comes with Add To Home Screen functionality which requires you to reupload your App Icon.';
								break;
							}
						}
					}
				?>

				<div id="wmp_editimages_warning" class="message-container warning" style="display:<?php echo ($warning_message != '') ? 'block':'none' ?>">
					<div class="wrapper">
						<div class="relative"><a class="close-x"></a></div>
						<span><?php echo $warning_message; ?></span>
					</div>
					<div class="spacer-10"></div>
				</div>
                <div class="left">
                    <form name="wmp_editimages_form" id="wmp_editimages_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_theme_editimages&type=upload" method="post" enctype="multipart/form-data">

                        <?php
                            $logo_path = WMobilePack_Options::get_setting('logo');

                            if ($logo_path != "") {

                                if (!file_exists(WMP_FILES_UPLOADS_DIR . $logo_path))
                                    $logo_path = '';
                                else
                                    $logo_path = WMP_FILES_UPLOADS_URL . $logo_path;
                            }

                        ?>

                        <!-- upload logo field -->
                        <div class="wmp_editimages_uploadlogo" style="display: <?php echo $logo_path == '' ? 'block' : 'none';?>;">

                            <label for="wmp_editimages_logo">Upload your app logo</label>

                            <div class="custom-upload">

                                <input type="file" id="wmp_editimages_logo" name="wmp_editimages_logo" />
                                <div class="fake-file">
                                    <input type="text" id="fakefilelogo" disabled="disabled" />
                                    <a href="#" class="btn grey smaller">Browse</a>
                                </div>


                                <a href="javascript:void(0)" id="wmp_editimages_logo_removenew" class="remove" style="display: none;"></a>
                            </div>

                            <!-- cancel upload logo button -->
                            <div class="wmp_editimages_changelogo_cancel cancel-link" style="display: none;">
                                <a href="javascript:void(0);" class="cancel">cancel</a>
                            </div>
                            <div class="field-message error" id="error_logo_container"></div>

                        </div>

                        <!-- logo image -->
                        <div class="wmp_editimages_logocontainer display-logo" style="display: <?php echo $logo_path != '' ? 'block' : 'none';?>;">

                            <label for="branding_logo">App logo</label>
                            <div class="img" id="wmp_editimages_currentlogo" style="background:url(<?php echo $logo_path;?>); background-size:contain; background-repeat: no-repeat; background-position: center"></div>

                            <!-- edit/delete logo links -->
                            <a href="javascript:void(0);" class="wmp_editimages_changelogo btn grey smaller edit">Change</a>
                            <a href="#" class="wmp_editimages_deletelogo smaller remove">remove</a>

                        </div>

                        <div class="spacer-20"></div>

                        <?php
                            $icon_path = WMobilePack_Options::get_setting('icon');

                            if ($icon_path != "") {

                                if (!file_exists(WMP_FILES_UPLOADS_DIR . $icon_path))
                                    $icon_path = '';
                                else
                                    $icon_path = WMP_FILES_UPLOADS_URL . $icon_path;
                            }
                        ?>

                        <!-- upload icon field -->
                        <div class="wmp_editimages_uploadicon" style="display: <?php echo $icon_path == '' ? 'block' : 'none';?>;">

                            <label for="wmp_editimages_icon">Upload your app icon</label>

                            <div class="custom-upload">

                                <input type="file" id="wmp_editimages_icon" name="wmp_editimages_icon" />
                                <div class="fake-file">
                                    <input type="text" id="fakefileicon" disabled="disabled" />
                                    <a href="#" class="btn grey smaller">Browse</a>
                                </div>

                                <a href="javascript:void(0)" id="wmp_editimages_icon_removenew" class="remove" style="display: none;"></a>
                            </div>
                            <!-- cancel upload icon button -->
                            <div class="wmp_editimages_changeicon_cancel cancel-link" style="display: none;">
                                <a href="javascript:void(0);" class="cancel">cancel</a>
                            </div>
                            <div class="field-message error" id="error_icon_container"></div>

                        </div>

                        <!-- icon image -->
                        <div class="wmp_editimages_iconcontainer display-icon" style="display: <?php echo $icon_path != '' ? 'block' : 'none';?>;;">

                            <label for="branding_icon">App icon</label>
                            <img src="<?php echo $icon_path;?>" id="wmp_editimages_currenticon" />

                            <!-- edit/delete icon links -->
                            <a href="javascript:void(0);" class="wmp_editimages_changeicon btn grey smaller edit">Change</a>
                            <a href="#" class="wmp_editimages_deleteicon smaller remove">remove</a>
						</div>

						<div class="spacer-20"></div>

						<a href="javascript:void(0);" id="wmp_editimages_send_btn" class="btn green smaller">Save</a>

                    </form>
                </div>

                <div class="notice notice-left right" style="width: 265px;">
                    <span>
                        Add your logo in a .png format with a transparent background. This will be displayed on the cover of your app.<br /><br />
                        Your icon should be square with a recommended size of 512 x 512 px. This will be displayed when the app will be added to the homescreen.<br /><br />
                        The file size for uploaded images should not exceed 1MB.
                    </span>
                </div>
                <div class="spacer-0"></div>
			</div>

			<div class="spacer-15"></div>


            <div class="spacer-15"></div>

 
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
