<?php


?>




<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>

<?php
// 							function read_manifest_from_disk(){

// 								$man = $_SERVER['DOCUMENT_ROOT'].'/manifest.json';

// 								$skim = fopen($man, 'r');

// 								$man_update_out = fread($skim, filesize($man)); 
// 								fclose($skim);
// 								$man_update_in = json_decode($man_update_out, true);

// 								return $man_update_out;
// 							}


// 							 function write_manifest_to_disk() {
//     						$man = $_SERVER['DOCUMENT_ROOT'].'/manifest.json';


//     						$man_link = fopen($man, 'a') or die("Can't open file");

//     						fwrite($man_link, $man_update_out);

//     						fclose($man_link);

//     						echo 'The file has been written to: '.$_SERVER['DOCUMENT_ROOT'].'/manifest.json';
// } 


// 							$man = $_SERVER['DOCUMENT_ROOT'].'/manifest.json';

// 							if(isset($_POST["save"])) {



// 							$defaults = array(
// 							'name' => get_bloginfo('name').'|'.get_bloginfo('description'),
// 							'short_name' => (mb_strstr(get_bloginfo('name'), ' ', true, 'utf-8') ) ? mb_strstr(get_bloginfo('name'), ' ', true, 'utf-8') : get_bloginfo('name'),
// 							'description' => get_bloginfo('description'),
// 							'background-color' => '#E4E4E4',
// 							'theme_color' => '#E4E4E4',
// 							'start_url' => trailingslashit(get_bloginfo('url')),
// 							'display' => 'standalone',
// 							'orientation' => 'portrait',
// 						); 

    					
// 							$man = $_SERVER['DOCUMENT_ROOT'].'/manifest.json';
// 						}

// 						if(file_exists($man)) {

// 							read_manifest_from_disk();

// 						}


//     						$man_update_in[] = $defaults;
    						
//     						$man_update_out = json_encode($man_update_in);

//     					write_manifest_to_disk();	



// 						?>


// 						<?php
// 							$user_settings = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/wordpress-pwa/theme.json';
// 							$success = ' ';
// 							$failure = ' ';

// 							if(isset($_POST["save"])) {

// 							$input_proc = array(

// 									'bmBurgerBarsBackground' => $_POST['bmBurgerBarsBackground'],
// 									'bmCrossBackground' => $_POST['bmCrossBackground'],
// 									'bmMenuBackground' => $_POST['bmMenuBackground'],
// 									'bmItemListColor' => $_POST['bmItemListColor'],
// 									'selectedBackground' => $_POST['selectedBackground'],
// 									'selectedText' => $_POST['selectedText'],
// 									'themeColour' => $_POST['themeColour'],
// 									'backgroundColour' => $_POST['backgroundColour']

// 							);

// 							$user_settings = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/wordpress-pwa/theme.json';


// 							if(file_exists($user_settings)) {

// 								$output = file_get_contents($user_settings); //Need to substitute for fread.

// 								$array_data = json_decode($output, true);
						
// 							}


// 							$array_data[] = $input_proc; #appends the array with new form data.
				
// 							$output = json_encode($array_data);

// 							#file_put_contents($user_settings, $output);
				 

// 							if(file_put_contents($user_settings, $output)) {

// 								$success = "<label class='text-success'> Your settings have been saved. </label>";
// 								}
// 							else {
// 								$failure = 'JSON file does not exist';
// 							} 	
// 						}		
 						?>

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
							<p class="section-header">Select Colour Scheme</p>
							<div class="spacer-20"></div>
							<form method="post" id="color-settings" enctype="multipart/form-data">
								<?php 

								if(isset($failure)) {

									echo $failure;
								}
								?>
								<div class="holder">
									<label>Mobile Menu Bar Background Colour</label><input class="bmBurgerBarsBackground" type="text" name="bmBurgerBarsBackground" id="bmBurgerBarsBackground" placeholder="Enter hex value" onkeyup="changeColour(this.className); saveValue(this)" /><div class="changedElement" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour(className){
											document.getElementsByClassName("changedElement")[0].style.background = document.getElementsByClassName("bmBurgerBarsBackground")[0].value;
										}
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
									<input type="text" class="bmCrossBackground" name="bmCrossBackground" id="bmCrossBackground" placeholder="Enter hex value" onkeyup="changeColour2(this.className);" /><div class="changedElement2" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
										<script>
										function changeColour2(className){
											document.getElementsByClassName("changedElement2")[0].style.background = document.getElementsByClassName("bmCrossBackground")[0].value;
										}
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="bmMenuBackground">Mobile Menu Background Colour</label>
									<input class="bmMenuBackground" type="text" name="bmMenuBackground" placeholder="Enter hex value" onkeyup="changeColour3(this.className)" /><div class="changedElement3" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour3(className){
											document.getElementsByClassName("changedElement3")[0].style.background = document.getElementsByClassName("bmMenuBackground")[0].value;
										}
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="bmItemListColor">Mobile Menu Item List Colour</label>
									<input class="bmItemListColor" type="text" name="bmItemListColor" placeholder="Enter hex value" onkeyup="changeColour4(this.className)" /><div class="changedElement4" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour4(className){
											document.getElementsByClassName("changedElement4")[0].style.background = document.getElementsByClassName("bmItemListColor")[0].value;
										}
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="selectedBackground">Selected Item Background Colour</label>
									<input class="selectedBackground" type="text" name="selectedBackground" placeholder="Enter hex value" onkeyup="changeColour5(this.className)" /><div class="changedElement5" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour5(className){
											document.getElementsByClassName("changedElement5")[0].style.background = document.getElementsByClassName("selectedBackground")[0].value;
										}
									</script>
								</div>			
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="selectedText">Selected Text Background Color</label>
									<input class="selectedText" type="text" name="selectedText" placeholder="Enter hex value" onkeyup="changeColour6(this.className)" /><div class="changedElement6" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour6(className){
											document.getElementsByClassName("changedElement6")[0].style.background = document.getElementsByClassName("selectedText")[0].value;
										}
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="themeColour">Theme Colour</label>
									<input class="themeColour" type="text" name="themeColour" placeholder="Enter hex value" onkeyup="changeColour7(this.className)" /><div class="changedElement7" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour7(className){
											document.getElementsByClassName("changedElement7")[0].style.background = document.getElementsByClassName("selectedText")[0].value;
										}
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="backgroundColour">Background Colour</label>
									<input class="backgroundColour" type="text" name="backgroundColour" placeholder="Enter hex value" onkeyup="changeColour8(this.className)" /><div class="changedElement8" style="height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
									<script>
										function changeColour8(className){
											document.getElementsByClassName("changedElement8")[0].style.background = document.getElementsByClassName("selectedText")[0].value;
										}
									</script>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="headerImage">Header Image</label>
									<input class="headerImage" type="file" name="headerImage" id="headerImage"/>
									<?php

									function header_retrieve(){
										if(isset($_FILES['headerImage'])){
											$headerImage = $_FILES['header'];
											$uploaded1 = media_handle_upload('headerImage', 0);

											if(is_wp_error($uploaded1)){
												echo "There was a problem uploading the file. Please try again.". $uploaded1->get_error_message();
											} else {
												echo "The file has been uploaded successfully.";
											}
										}
									} 
									?>
									<?php header_retrieve(); ?>
									<?php submit_button('Upload') ?>
								</div>
								<div class="spacer-15"></div>	
								<div class="holder">
									<label for="hamburgerImage">Mobile Menu Bar Image</label>
									<input class="hamburgerImage" type="file" name="hamburgerImage" id="hamburgerImage"/>
										<?php

									function hamburger_retrieve(){
										if(isset($_FILES['hamburgerImage'])){
											$headerImage = $_FILES['header'];
											$uploaded2 = media_handle_upload('hamburgerImage', 0);

											if(is_wp_error($uploaded2)){
												echo "There was a problem uploading the file. Please try again. ". $uploaded2->get_error_message();
											} else {
												echo "The file has been uploaded successfully.";
											}
										}
									} 
									?>
									<?php hamburger_retrieve(); ?>
									<?php submit_button('Upload') ?>
								</div>
								<div class="spacer-15"></div>
								<div class="holder">
									<label for="loadingSpinner">Loading Spinner Image</label>
									<input class="loadingSpinner" type="file" name="loadingSpinner" id="loadingSpinner"/>
									<?php

									function spinner_retrieve(){
										if(isset($_FILES['loadingSpinner'])){
											$headerImage = $_FILES['header'];
											$uploaded3 = media_handle_upload('loadingSpinner', 0);

											if(is_wp_error($uploaded3)){
												echo "There was a problem uploading the file. Please try again. ". $uploaded3->get_error_message();
											} else {
												echo "The file has been uploaded successfully.";
											}
										}
									} 
									?>
									<?php spinner_retrieve(); ?>
									<?php submit_button('Upload') ?>
								</div>
						<div class="spacer-20"></div>
						<div class="submit"><input type="submit" name="save" class="save" value="Save"/></div>	
							<?php 

							if(isset($success)) {

									echo $success;

									print_r($output);
							}

							?>	
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
