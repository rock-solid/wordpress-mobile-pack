<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){

            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<div id="wmpack-admin">
	<div class="spacer-60"></div>
    <!-- set title -->
    <h1><?php echo WMP_PLUGIN_NAME.' '.WMP_VERSION;?></h1>
	<div class="spacer-20"></div>
	<div class="look-and-feel">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>

            <!-- add content form -->
            <div class="details">
                <div class="spacer-10"></div>
                <p><p>Customize your mobile web application by choosing from the below color schemes &amp; fonts, adding your logo and app icon. The default theme comes with 6 abstract covers that are randomly displayed on the loading screen to give your app a magazine flavor. You can further personalize your mobile web application by uploading your own cover.</p>
                <div class="spacer-20"></div>
            </div>
            <div class="spacer-10"></div>

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

					<form name="wmp_edittheme_form" id="wmp_edittheme_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_theme_settings" method="post">

						<div class="color-schemes">
							<p class="section-header">Select Color Scheme</p>
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

							<!-- add custom scheme radio button -->
							<input type="radio" name="wmp_edittheme_colorscheme" id="wmp_edittheme_colorscheme" value="0" <?php echo $selected_color_scheme == 0 ? 'checked="checked"' : '';?> autocomplete="off" />
							<p>Edit custom colors</p>
						</div>

						<!-- start notice -->
						<div class="notice notice-left left" style="width: 50%;">
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
						<div class="color-schemes-custom" style="display: <?php echo $selected_color_scheme == 0 ? 'block' : 'none';?>;">

							<p class="section-header">Your Custom Scheme</p>
							<div class="spacer-20"></div>

							<div class="set">
								<?php
									// display color pickers and divide them into two columns
									$half = ceil( count($theme_settings['labels']) / 2);

									// read the custom colors options array
									$selected_custom_colors = WMobilePack_Options::get_setting('custom_colors');

									foreach ($theme_settings['labels'] as $key => $description):

										$color_value = '';
										if (!empty($selected_custom_colors) && array_key_exists($key, $selected_custom_colors))
											$color_value = $selected_custom_colors[$key];
								?>
									<label for="wmp_edittheme_customcolor<?php echo $key;?>"><?php echo ($key+1).'. '.$description;?></label>
									<input type="text" name="wmp_edittheme_customcolor<?php echo $key;?>" id="wmp_edittheme_customcolor<?php echo $key;?>" value="<?php echo $color_value;?>" autocomplete="off" />
									<div class="spacer-10"></div>

									<?php if ($key + 1 == $half):?>
										</div>
										<div class="set">
									<?php endif;?>

								<?php endforeach;?>
							</div>
						</div>
						<div class="spacer-20"></div>

						<!-- choose fonts -->
						<div class="font-chooser">
							<p class="section-header">Select Fonts</p>
							<div class="spacer-20"></div>

							<!-- add radio buttons -->
							<?php if (array_key_exists('headlines-font', $theme_settings['fonts'])): ?>
								<?php
									$font_headlines = WMobilePack_Options::get_setting('font_headlines');
									if ($font_headlines == '')
										$font_headlines = 1;
								?>

								<label for="wmp_edittheme_fontheadlines">Headlines</label>

								<select name="wmp_edittheme_fontheadlines" id="wmp_edittheme_fontheadlines">

									<?php foreach (WMobilePack_Themes_Config::$allowed_fonts as $key => $font_family): ?>
										<option value="<?php echo $key+1;?>" data-text='<span style="font-family:<?php echo str_replace(" ", "", $font_family);?>"><?php echo $font_family;?></span>' <?php if ($font_headlines == $key+1) echo "selected";?>></option>
									<?php endforeach; ?>
								</select>

								<div class="spacer-10"></div>
							<?php endif; ?>

							<?php if (array_key_exists('subtitles-font', $theme_settings['fonts'])): ?>
								<?php
									$font_subtitles = WMobilePack_Options::get_setting('font_subtitles');
									if ($font_subtitles == '')
										$font_subtitles = 1;
								?>

								<label for="wmp_edittheme_fontsubtitles">Subtitles</label>
								<select name="wmp_edittheme_fontsubtitles" id="wmp_edittheme_fontsubtitles">
									<?php foreach (WMobilePack_Themes_Config::$allowed_fonts as $key => $font_family): ?>
										<option value="<?php echo $key+1;?>" data-text='<span style="font-family:<?php echo str_replace(" ", "", $font_family);?>"><?php echo $font_family;?></span>' <?php if ($font_subtitles == $key+1) echo "selected";?>></option>
									<?php endforeach; ?>
								</select>
								<div class="spacer-10"></div>
							<?php endif; ?>

							<?php if(array_key_exists('paragraphs-font', $theme_settings['fonts'])): ?>
								<?php
									$font_paragraphs = WMobilePack_Options::get_setting('font_paragraphs');
									if ($font_paragraphs == '')
										$font_paragraphs = 1;
								?>

								<label for="wmp_edittheme_fontparagraphs">Paragraphs</label>
								<select name="wmp_edittheme_fontparagraphs" id="wmp_edittheme_fontparagraphs">
									<?php foreach (WMobilePack_Themes_Config::$allowed_fonts as $key => $font_family): ?>
										<option value="<?php echo $key+1;?>" data-text='<span style="font-family:<?php echo str_replace(" ", "", $font_family);?>"><?php echo $font_family;?></span>' <?php if ($font_paragraphs == $key+1) echo "selected";?>></option>
									<?php endforeach; ?>
								</select>
								<div class="spacer-20"></div>
							<?php endif;?>
						</div>

						<div class="spacer-20"></div>
						<a href="javascript:void(0);" id="wmp_edittheme_send_btn" class="btn green smaller" >Save</a>
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
								$warning_message = 'WP Mobile Pack Version 3.2 comes with Add To Home Screen functionality which requires you to reupload your App Icon.';
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
                        Your icon should be square with a recommended size of 256 x 256 px. This will be displayed when the app will be added to the homescreen.<br /><br />
                        The file size for uploaded images should not exceed 1MB.
                    </span>
                </div>
                <div class="spacer-0"></div>
			</div>

			<div class="spacer-15"></div>
			<div class="details">
			<div class="display-mode">
				<h2 class="title">Add to Home Screen</h2>
				<div class="spacer-20"></div>
				<p>In order for your users to be prompted to add the app to their home screen you must add a service worker to the root of your domain.</p>
				<div class="spacer-10"></div>
				<p>Move the 'sw.js' file which is located in the 'wordpress-mobile-pack' plugin directory to the root of your domain '/' using FTP.</p>
				<div class="spacer-10"></div>
				<p>Once you have moved the file to your root, check the box bellow and click 'save'. For more details visit the <a href="https://docs.wpmobilepack.com/wp-mobile-pack-free/look-and-feel.html" target="_blank">support page.</a></p>
				<div class="spacer-30"></div>
				<form name="wmp_service_worker_form" id="wmp_service_worker_form" class="left" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_save" method="post" style="min-width: 300px;">
					<?php $installed = WMobilePack_Options::get_setting('service_worker_installed'); ?>
					<input type="hidden" name="wmp_option_service_worker_installed" id="wmp_option_service_worker_installed" value="<?php echo $installed; ?>"/>
					<input type="checkbox" name="wmp_service_worker_installed_check" id="wmp_service_worker_installed_check" value="1" <?php if ($installed == 1) echo "checked" ;?> />
					<label for="wmp_service_worker_installed_check"> Service Worker Installed </label>
					<div class="spacer-40"></div>
					<a href="javascript:void(0);" id="wmp_service_worker_send_btn" class="btn green smaller">Save</a>
				</form>
			</div>
			<div class="spacer-0"></div>
			</div>


            <div class="spacer-15"></div>

                <div class="details branding">

				<h2 class="title">Customize Your App's Cover</h2>
				<div class="spacer-15"></div>
				<div class="grey-line"></div>
				<div class="spacer-20"></div>
				<p>Each theme comes with 6 abstract covers that are randomly displayed on the loading screen to give your app a magazine flavor. You can further personalize your mobile web application by uploading your own cover.</p>
				<div class="spacer-20"></div>

				<form name="wmp_editcover_form" id="wmp_editcover_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_theme_editimages&type=upload" method="post" enctype="multipart/form-data">
					<div class="left">
						<?php
							$cover_path = WMobilePack_Options::get_setting('cover');

							if ($cover_path != "") {

								if (!file_exists(WMP_FILES_UPLOADS_DIR . $cover_path))
									$cover_path = '';
								else
									$cover_path = WMP_FILES_UPLOADS_URL . $cover_path;
							}
						?>

						<!-- upload cover field -->
						<div class="wmp_editcover_uploadcover" style="display: <?php echo $cover_path == '' ? 'block' : 'none';?>;">

							<label for="wmp_editcover_cover">Upload your app cover:</label>

							<div class="custom-upload">

								<input type="file" id="wmp_editcover_cover" name="wmp_editcover_cover" />
								<div class="fake-file">
									<input type="text" id="fakefilecover" disabled="disabled" />
									<a href="#" class="btn grey smaller">Browse</a>
								</div>

								<a href="javascript:void(0)" id="wmp_editcover_cover_removenew" class="remove" style="display: none;"></a>
							</div>

							<!-- cancel upload cover button -->
							<div class="wmp_editcover_changecover_cancel cancel-link" style="display: none;">
								<a href="javascript:void(0);" class="cancel">cancel</a>
							</div>
							<div class="field-message error" id="error_cover_container"></div>

						</div>

						<!-- cover image -->
						<div class="wmp_editcover_covercontainer display-logo" style="display: <?php echo $cover_path != '' ? 'block' : 'none';?>;">

							<label for="branding_cover">App cover:</label>
							<div class="img" id="wmp_editcover_currentcover" style="background:url(<?php echo $cover_path;?>); background-size:contain; background-repeat: no-repeat; background-position: center"></div>

							<!-- edit/delete cover links -->
							<a href="javascript:void(0);" class="wmp_editcover_changecover btn grey smaller edit">Change</a>
							<a href="#" class="wmp_editcover_deletecover smaller remove">remove</a>

						</div>
					</div>

					<div class="notice notice-left right" style="width: 265px;">
						<span>
						   Your cover will be used in portrait and landscape modes, so choose something that will look good on different screen sizes. <br />
						   We recommend using a square image of minimum 500 x 500 px. The file size for uploaded images should not exceed 1MB.
						</span>
					</div>

					<div class="spacer-20"></div>
					<a href="javascript:void(0);" id="wmp_editcover_send_btn" class="btn green smaller">Save</a>

				</form>
				<div class="spacer-0"></div>
			</div>
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

            window.WMPJSInterface.add("UI_customizetheme","WMP_EDIT_THEME",{'DOMDoc':window.document}, window);
            window.WMPJSInterface.add("UI_editimages","WMP_EDIT_IMAGES",{'DOMDoc':window.document}, window);
			window.WMPJSInterface.add("UI_editcover","WMP_EDIT_COVER",{'DOMDoc':window.document}, window);
			window.WMPJSInterface.add("UI_service_worker","WMP_SERVICE_WORKER",{'DOMDoc':window.document}, window);

        });
    }
</script>
