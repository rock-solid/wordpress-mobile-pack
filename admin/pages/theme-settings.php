<?php

$themeManager = new PtPwaThemeManager(new PtPwaTheme());
$theme = $themeManager->getTheme();

$manifestManager = new PtPwaManifestManager(new PtPwaManifest());
$manifest = $manifestManager->getManifest();

if (!empty($_POST['save'])) {
	if (!empty($_FILES['logo']['name'])) {
		$logoUploaded = media_handle_upload('logo', 0);
		$logoUrl = wp_get_attachment_url($logoUploaded);

		if (is_wp_error($logoUploaded)) {
			$logoMsg = "There was a problem uploading the file. Please try again." . $logoUploaded->get_error_message();
		} else {
			$theme->setHeaderImage($logoUrl);
			$theme->setHamburgerImage($logoUrl);
			$logoMsg = "The file has been uploaded successfully.";
		}
	}

	if (!empty($_FILES['appIcon']['name'])) {
		$appIconUploaded = media_handle_upload('appIcon', 0);
		$mimeType = get_post_mime_type($appIconUploaded);
		$appIconArray = array(
			array(
				"src" => wp_get_attachment_image_src($appIconUploaded, 'pwa-x-small')[0],
				"sizes" => "180x180",
				"type" => $mimeType
			),
			array(
				"src" => wp_get_attachment_image_src($appIconUploaded, 'pwa-small')[0],
				"sizes" => "192x192",
				"type" => $mimeType
			),
			array(
				"src" => wp_get_attachment_image_src($appIconUploaded, 'pwa-medium')[0],
				"sizes" => "384x384",
				"type" => $mimeType
			),
			array(
				"src" => wp_get_attachment_image_src($appIconUploaded, 'pwa-large')[0],
				"sizes" => "512x512",
				"type" => $mimeType
			),
		);
		
		if (is_wp_error($appIconUploaded)) {
			$appIconMsg = "There was a problem uploading the file. Please try again. " . $appIconUploaded->get_error_message();
		} else {
			$manifest->setIcons($appIconArray);
			$appIconMsg = "The file has been uploaded successfully.";
		}
	}

	if (!empty($_FILES['loadingSpinner']['name'])) {
		$loadingSpinnerUploaded = media_handle_upload('loadingSpinner', 0);
		$loadingSpinnerUrl = wp_get_attachment_url($loadingSpinnerUploaded);
		if (is_wp_error($loadingSpinnerUploaded)) {
			$loadingSpinnerMsg = "There was a problem uploading the file. Please try again. " . $loadingSpinnerUploaded->get_error_message();
		} else {
			$theme->setLoadingSpinner($loadingSpinnerUrl);
			$loadingSpinnerMsg = "The file has been uploaded successfully.";
		}
	}

	// Theme Colours
	$theme->setBmBurgerBarsBackground($_POST['bmBurgerBarsBackground']);
	$theme->setBmCrossBackground($_POST['bmCrossBackground']);
	$theme->setBmMenuBackground($_POST['bmMenuBackground']);
	$theme->setSelectedText($_POST['selectedText']);
	$theme->setThemeColour($_POST['themeColour']);
	$theme->setBackgroundColour($_POST['backgroundColour']);
	$theme->setTextColour($_POST['textColour']);
	$theme->setSectionSliderTextColor($_POST['sectionSliderTextColor']);
	$theme->setSectionSliderBackground($_POST['sectionSliderBackground']);
	$theme->setHighlightsColour($_POST['highlightsColour']);
	$theme->setBorderColour($_POST['borderColour']);

	// Theme Details
	$theme->setSectionDownloadEnabled(isset($_POST['sectionDownloadEnabled']));
	$theme->setMultiSection(isset($_POST['multiSection']));
	$theme->setShowDateBlockOnFeedListItem(isset($_POST['showDateBlockOnFeedListItem']));
	$theme->setShowAllFeed(isset($_POST['showAllFeed']));
	$theme->setImageGalleryHeight($_POST['imageGalleryHeight']);
	$theme->setShowDatesOnList(isset($_POST['showDatesOnList']));
	$theme->setSearchLightTheme(isset($_POST['searchLightTheme']));
	$theme->setShowSearch(isset($_POST['showSearch']));
	$theme->setTopHeros((int) preg_replace('/[^0-9]/', '', $_POST['topHeros']));
	$theme->setShareTitlePrefix($_POST['shareTitlePrefix']);
	$theme->setInfiniteVerticalArticleScroll(isset($_POST['infiniteVerticalArticleScroll']));
	$theme->setInfiniteHorizontalArticleScroll(isset($_POST['infiniteHorizontalArticleScroll']));
	$theme->setNewsItemTimeFormat($_POST['newsItemTimeFormat']);
	$theme->setNewsItemDateFormat($_POST['newsItemDateFormat']);
	$theme->setDefaultFeedPageSize((int) preg_replace('/[^0-9]/', '', $_POST['defaultFeedPageSize']));
	$theme->setDnsPrefetch(explode(',', $_POST['dnsPrefetch']));
	
	// Manifest Colours
	$manifest->setThemeColor($_POST['themeColour']);
	$manifest->setBackgroundColor($_POST['backgroundColour']);

	$manifestManager->write();
	$themeManager->write();
}

?>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null) {
		function changeColour(input) {
			input.nextElementSibling.style.background = input.value;
		}
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
    border: 2px solid #0c4b7f;
    border-radius: 3px;
    padding: 7px 15px 7px 15px;
    min-width: 120px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}

.save:hover {
    background: #FFF;
    color: #0c4b7f;
}

.button.wp-color-result {
	min-width: 255px;
	min-height: 36px;
}

</style>

<div id="wmpack-admin">
	<div class="spacer-60"></div>

	<!-- set title -->
	<h1>Publisher's Toolbox PWA <?php echo $Pt_Pwa_Config->PWA_VERSION; ?></h1>
	<div class="spacer-20"></div>

	<div class="look-and-feel">
		<div class="left-side">
		<!-- add nav menu -->
		<?php include_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/sections/admin-menu.php'); ?>
		<div class="spacer-0"></div>

		<!-- add content form -->
				<div class="details">
					<p class="title">Select Colour Scheme</p>
						<div class="spacer-20"></div>
							<form method="post" id="color-settings" enctype="multipart/form-data">
								<div class="holder">
									<label>Menu icon colour</label>
									<input value="<?= $theme->getBmBurgerBarsBackground() ?>" class="bmBurgerBarsBackground" type="text" name="bmBurgerBarsBackground" id="bmBurgerBarsBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div class="changedElement" style="background:<?= $theme->getBmBurgerBarsBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
								</div>	
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="bmCrossBackground">Close button colour</label>
									<input value="<?= $theme->getBmCrossBackground() ?>"   type="text" class="bmCrossBackground" name="bmCrossBackground" id="bmCrossBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div class="changedElement2" style="background:<?= $theme->getBmCrossBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="bmMenuBackground">Menu background colour</label>
									<input value="<?= $theme->getBmMenuBackground() ?>" class="bmMenuBackground" type="text" name="bmMenuBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div class="changedElement3" style="background:<?= $theme->getBmMenuBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="selectedText">Selected text colour</label>
									<input value="<?= $theme->getSelectedText() ?>" class="selectedText" type="text" name="selectedText" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div class="changedElement6" style="background:<?= $theme->getSelectedText() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="themeColour">Theme colour</label>
									<input value="<?= $theme->getThemeColour() ?>" class="themeColour" type="text" name="themeColour" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div class="changedElement7" style="background:<?= $theme->getThemeColour() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="backgroundColour">Background colour</label>
									<input  value="<?= $theme->getBackgroundColour() ?>" class="backgroundColour" type="text" name="backgroundColour" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div class="changedElement8" style="background:<?= $theme->getBackgroundColour() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="textColour">Text colour</label>
									<input  value="<?= $theme->getTextColour() ?>" class="textColour" type="text" name="textColour" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div style="background:<?= $theme->getTextColour() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="sectionSliderTextColor">Navigation menu text colour</label>
									<input  value="<?= $theme->getSectionSliderTextColor() ?>" class="sectionSliderTextColor" type="text" name="sectionSliderTextColor" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div style="background:<?= $theme->getSectionSliderTextColor() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="sectionSliderBackground">Navigation menu background colour</label>
									<input  value="<?= $theme->getSectionSliderBackground() ?>" class="sectionSliderBackground" type="text" name="sectionSliderBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div style="background:<?= $theme->getSectionSliderBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="highlightsColour">Highlights colour</label>
									<input  value="<?= $theme->getHighlightsColour() ?>" class="highlightsColour" type="text" name="highlightsColour" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div style="background:<?= $theme->getHighlightsColour() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="borderColour">Border colour</label>
									<input  value="<?= $theme->getBorderColour() ?>" class="borderColour" type="text" name="borderColour" placeholder="Enter hex value" onkeyup="changeColour(this);" />
									<div style="background:<?= $theme->getBorderColour() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
								</div>
								<div class="spacer-15"></div>

								<input type="checkbox" name="sectionDownloadEnabled" <?= $theme->getSectionDownloadEnabled() ? 'checked' : '' ?> /> Section download enabled
								<div class="spacer-20"></div>
								
								<input type="checkbox" name="multiSection" <?= $theme->getMultiSection() ? 'checked' : '' ?> /> Show child categories
								<div class="spacer-20"></div>
								
								<input type="checkbox" name="showDateBlockOnFeedListItem" <?= $theme->getShowDateBlockOnFeedListItem() ? 'checked' : '' ?> /> Show date on feed items
								<div class="spacer-20"></div>
								
								<input type="checkbox" name="showAllFeed" <?= $theme->getShowAllFeed() ? 'checked' : '' ?> /> Show home section
								<div class="spacer-20"></div>
								
								<div class="holder">
									<label for="imageGalleryHeight">Image gallery height (as px or vh value)</label>
									<input  value="<?= $theme->getImageGalleryHeight() ?>" class="imageGalleryHeight" type="text" name="imageGalleryHeight" placeholder="Image gallery height" />
								</div>
								<div class="spacer-15"></div>

								<input type="checkbox" name="showDatesOnList" <?= $theme->getShowDatesOnList() ? 'checked' : '' ?> /> Show dates on Image Thumbnail
								<div class="spacer-20"></div>
								
								<input type="checkbox" name="showSearch" <?= $theme->getShowSearch() ? 'checked' : '' ?> /> Show menu search bar
								<div class="spacer-20"></div>
								
								<input type="checkbox" name="searchLightTheme" <?= $theme->getSearchLightTheme() ? 'checked' : '' ?> /> Menu search bar foreground light theme
								<div class="spacer-20"></div>
								
								<div class="holder">
									<label for="topHeros">Top Featured posts</label>
									<input  value="<?= $theme->getTopHeros() ?>" class="topHeros" type="number" min="1" max="5" name="topHeros" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="shareTitlePrefix">Share Message Suffix</label>
									<input  value="<?= $theme->getShareTitlePrefix() ?>" class="shareTitlePrefix" type="text" name="shareTitlePrefix" placeholder="Share Message Suffix" />
								</div>
								<div class="spacer-15"></div>
								
								<input type="checkbox" name="infiniteVerticalArticleScroll" <?= $theme->getInfiniteVerticalArticleScroll() ? 'checked' : '' ?> /> Infinite vertical article scroll
								<div class="spacer-20"></div>
								
								<input type="checkbox" name="infiniteHorizontalArticleScroll" <?= $theme->getInfiniteHorizontalArticleScroll() ? 'checked' : '' ?> /> Infinite horizontal article scroll
								<div class="spacer-20"></div>

								<div class="holder">
									<label for="newsItemDateFormat">News item date format</label>
									<input  value="<?= $theme->getNewsItemDateFormat() ?>" class="newsItemDateFormat" type="dat" name="newsItemDateFormat" placeholder="eg Do MMM YYYY" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="newsItemTimeFormat">News item Time format</label>
									<input  value="<?= $theme->getNewsItemTimeFormat() ?>" class="newsItemTimeFormat" type="text" name="newsItemTimeFormat" placeholder="News item time format" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="defaultFeedPageSize">Feed page size</label>
									<input  value="10" class="defaultFeedPageSize" type="number" min="10" max="50" name="defaultFeedPageSize" />
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<label for="dnsPrefetch">DNS Prefetch list (seperated by comma) - <i>*optional</i></label>
									<textarea class="dnsPrefetch" type="textarea" name="dnsPrefetch" placeholder="DNS Prefetch List"><?= implode(",",$theme->getDnsPrefetch()) ?></textarea>
								</div>
								<div class="spacer-15"></div>

								<div class="holder">
									<img src="<?= $theme->getHeaderImage() ?>" style="max-height:80px" />
									<label for="logo">App logo</label>
									<input type="file" name="logo" style="padding: 7px;"/>
									<?= $logoMsg ?>
								</div>
								<div class="spacer-15" ></div>	

								<div class="holder">
									<img src="<?= $manifest->getIcons()[0]['src'] ?>" style="max-height:80px" />
									<label for="appIcon">App icon</label>
									<input type="file" name="appIcon" style="padding: 7px;"/>
									<?= $appIconMsg ?>
								</div>
								<div class="spacer-15"></div>

							<div class="submit">
								<input type="submit" name="save" class="save" />
							</div>		
						</form>	
				</div>
				<div class="spacer-15"></div>
        </div>
	</div>
</div>
