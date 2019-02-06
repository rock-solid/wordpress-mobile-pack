<?php

$themeManager = new PtPwaThemeManager(new PtPwaTheme());
$theme = $themeManager->getTheme();
$Pt_Pwa_Config = new Pt_Pwa_Config();

$manifestManager = new PtPwaManifestManager(new PtPwaManifest());
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
    $theme->setApiEndpoint($_POST['apiEndpoint']);
    $theme->setTwitterSocialUrl($_POST['twitterSocialUrl']);
    $theme->setFacebookSocialUrl($_POST['facebookSocialUrl']);
    $theme->setInstagramSocialUrl($_POST['instagramSocialUrl']);
    $theme->setYoutubeSocialUrl($_POST['youtubeSocialUrl']);
    $theme->setDFTNetworkId($_POST['DFTNetworkId']);

	$manifestManager->write();
	$themeManager->write();
    $Pt_Pwa_Config->enable_pwa(); // enable_pwa on save
}

?>

<style>

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

</style>

<div id="wmpack-admin">

    <?php include_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'admin/enable-pwa-btn.php'); ?>

	<div class="spacer-20"></div>

	<!-- set title -->
	<h1>Publisher's Toolbox PWA</h1>
	<div class="spacer-20"></div>

	<div class="settings">
		<div class="left-side">
		<!-- add nav menu -->
		<?php include_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/sections/admin-menu.php'); ?>
		<div class="spacer-0"></div>

		<!-- add content form -->
        <div class="details">
            <h2 class="title">App Settings</h2>
            <div class="spacer-20"></div>

            <div class="spacer-10"></div>

            <form id="core-settings" method="post" enctype="multipart/form-data">
                <label>Application name</label>
                <input type="text" name="appName" value="<?= $manifest->getName() ?>"/>
                <div class="spacer-20"></div>

                <label>Application meta description</label>
                <input type="text" name="description" value="<?= $manifest->getDescription() ?>"/> 
                <div class="spacer-20"></div>

                <label>Google Tag Manager ID</label>
                <input type="text" name="GTMID" value="<?= $theme->getGTMID() ?>" />
                <div class="spacer-20"></div>

                <label>Google Analytics tracking code</label>
                <input type="text" name="GATrackingCode" value="<?= $theme->getGATrackingCode() ?>" />
                <div class="spacer-20"></div>

                <label>Google Ad Manager network ID</label>
                <input type="text" name="DFTNetworkId" value="<?= $theme->getDFTNetworkId() ?>" />
                <div class="spacer-20"></div>

                <input type="hidden" name="apiEndpoint" value="<?= $theme->getApiEndpoint() ?>" />
                <div class="spacer-20"></div>

                <input type="checkbox" name="showClassicSwitch" <?= $theme->getShowClassicSwitch() ? 'checked' : '' ?> /> Show classic site switch
                <div class="spacer-20"></div>
                 
                <div class="spacer-0"></div>

                <h2 class="title">Social Media Sharing</h2>
                <div class="spacer-20"></div>

                <label>Twitter Social Link</label>
                <input type="text" name="twitterSocialUrl" value="<?= $theme->getTwitterSocialUrl() ?>" />
                <div class="spacer-20"></div>

                <label>Instagram Social Link</label>
                <input type="text" name="instagramSocialUrl" value="<?= $theme->getInstagramSocialUrl() ?>" />
                <div class="spacer-20"></div>

                <label>Facebook Social Link</label>
                    <input type="text" name="facebookSocialUrl" value="<?= $theme->getFacebookSocialUrl() ?>" />
                <div class="spacer-20"></div>

                <label>YouTube Social Link</label>
                    <input type="text" name="youtubeSocialUrl" value="<?= $theme->getYoutubeSocialUrl() ?>" />
                <div class="spacer-20"></div>

                <input type="checkbox" name="socialMedia[]" value="ssk-facebook" <?= in_array('ssk-facebook', $theme->getSocialShareKitButtons()) ? 'checked' : '' ?> /> Enable Facebook sharing 
                <div class="spacer-10"></div>

                <input type="checkbox" name="socialMedia[]" value="ssk-twitter" <?= in_array('ssk-twitter', $theme->getSocialShareKitButtons()) ? 'checked' : '' ?>  /> Enable Twitter sharing 
                <div class="spacer-10"></div>

                <input type="checkbox" name="socialMedia[]" value="ssk-whatsapp" <?= in_array('ssk-whatsapp', $theme->getSocialShareKitButtons()) ? 'checked' : '' ?>  /> Enable WhatsApp sharing 
                <div class="spacer-10"></div>

                <input type="hidden" name="appEndpoint" value="<?= $theme->getAppEndpoint() ?>" />
                
                <div class="submit">
                    <input type="submit" name="save" class="save" value="Save" />
                </div>  
            </form>            
        </div>
        <div class="right-side"></div>
	</div>
</div>
