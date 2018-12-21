<?php

$themeManager = new ThemeManager(new Theme());
$theme = $themeManager->getTheme();

$manifestManager = new ManifestManager(new Manifest());
$manifest = $manifestManager->getManifest();

if (isset($_POST["save"])) {
    
    // Manifest Details
    $manifest->setName($_POST['appName']);
    $manifest->setShortName($_POST['appName']);
    $manifest->setDescription($_POST['description']);
    
    // Theme Details
    $theme->setAppName($_POST['appName']);
    $theme->setShowClassicSwitch(isset($_POST['showClassicSwitch']));
    $theme->setMetaDescription($_POST['description']);
    $theme->setGTMID($_POST['GTMID']);
    $theme->setGATrackingCode($_POST['GATrackingCode']);
    $theme->setSocialShareKitButtons($_POST['socialMedia']);
    $theme->setAppEndpoint($_POST['appEndpoint']);

	$manifestManager->write();
	$themeManager->write();
}

?>

<style>

.save {
	background: #0c4b7f;
    color: #ffffff;
    border: 1px solid #7ea82f;
    border-radius: 3px;
    padding: 7px 15px 7px 15px;
    min-width: 120px;
}

</style>

<div id="wmpack-admin">
	<div class="spacer-60"></div>

	<!-- set title -->
	<h1>Publisher's Toolbox PWA <?php echo WMP_VERSION; ?></h1>
	<div class="spacer-20"></div>

	<div class="settings">
		<div class="left-side">
		<!-- add nav menu -->
		<?php include_once(WMP_PLUGIN_PATH . 'admin/sections/admin-menu.php'); ?>
		<div class="spacer-0"></div>

		<!-- add content form -->
        <div class="details">
            <h2 class="title">App Settings</h2>
            <div class="spacer-20"></div>

            <div class="spacer-10"></div>

            <form id="core-settings" method="post" enctype="multipart/form-data">
                <label>Application Name</label>
                <input type="text" name="appName" value="<?= $manifest->getName() ?>"/>
                <div class="spacer-20"></div>

                <label>Application Meta Description</label>
                <input type="text" name="description" value="<?= $manifest->getDescription() ?>"/> 
                <div class="spacer-20"></div>

                <label>Google Tag Manager ID</label>
                <input type="text" name="GTMID" value="<?= $theme->getGTMID() ?>" />
                <div class="spacer-20"></div>

                <label>Google Analytics Tracking Code</label>
                <input type="text" name="GATrackingCode" value="<?= $theme->getGATrackingCode() ?>" />
                <div class="spacer-20"></div>

                <label>PWA App Endpoint</label>
                <input type="text" name="appEndpoint" value="<?= $theme->getAppEndpoint() ?>" />
                <div class="spacer-20"></div>

                <input type="checkbox" name="showClassicSwitch" <?= $theme->getShowClassicSwitch() ? 'checked' : '' ?> /> Show Classic Site Switch
                <div class="spacer-20"></div>
                 
                <div class="spacer-0"></div>

                <h2 class="title">Social Media Sharing</h2>
                <div class="spacer-20"></div>

                <input type="checkbox" name="socialMedia[]" value="ssk-facebook" <?= in_array('ssk-facebook', $theme->getSocialShareKitButtons()) ? 'checked' : '' ?> /> Enable Facebook Sharing 
                <div class="spacer-10"></div>

                <input type="checkbox" name="socialMedia[]" value="ssk-twitter" <?= in_array('ssk-twitter', $theme->getSocialShareKitButtons()) ? 'checked' : '' ?>  /> Enable Twitter Sharing 
                <div class="spacer-10"></div>

                <input type="checkbox" name="socialMedia[]" value="ssk-google-plus" <?= in_array('ssk-google-plus', $theme->getSocialShareKitButtons()) ? 'checked' : '' ?>  /> Enable Google+ Sharing 
                <div class="spacer-10"></div>

                <input type="checkbox" name="socialMedia[]" value="ssk-whatsapp" <?= in_array('ssk-whatsapp', $theme->getSocialShareKitButtons()) ? 'checked' : '' ?>  /> Enable WhatsApp Sharing 
                <div class="spacer-10"></div>
                
                <div class="submit">
                    <input type="submit" name="save" class="save" value="Save" />
                </div>  
            </form>            
        </div>
        <div class="right-side"></div>
	</div>
</div>
