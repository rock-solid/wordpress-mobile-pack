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
	<div class="themes">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>

            <!-- add content form -->
            <div class="details">
                <div class="spacer-10"></div>
                <p>Customize your mobile web application by choosing from the below color schemes &amp; fonts, adding your logo and app icon. The default theme comes with 6 abstract covers that are randomly displayed on the loading screen to give your app a magazine flavor. You can further personalize your mobile web application by uploading your own cover.</p>
                <div class="spacer-20"></div>
            </div>
            <div class="spacer-10"></div>

            <div class="details theming">
                <h2 class="title">Choose Your Mobile Theme</h2>
                <div class="spacer_15"></div>
                <div class="spacer-15"></div>
                <div class="themes">

                    <?php
                        $arr_themes_names = array(
                            1 => 'Obliq',
                            2 => 'Mosaic',
                            3 => 'Base',
                            4 => 'Elevate',
                            5 => 'Folio'
                        );

                        $premium_link = isset($wpmp_upgrade_pro_link) ? $wpmp_upgrade_pro_link : WMobilePack_Admin::upgrade_pro_link();

                        for ($i = 1; $i <= 5; $i++):
                    ?>

                        <div class="theme <?php echo $i >= 2 ? 'premium' : '';?>">
                            <div class="corner relative <?php echo $i == 1 ? 'active' : '';?>">
                                <div class="indicator"></div>
                            </div>
                            <div class="image" style="background:url(<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/themes/theme-<?php echo $i;?>.jpg);">
                                <div class="relative">
                                    <div class="overlay">
                                        <div class="spacer-<?php echo $i >= 2 ? '70' : '100'; ?>"></div>
                                        <div class="actions">
                                            <div class="preview" id="wmp_themes_preview_<?php echo $i;?>"></div>
                                        </div>
                                        <div class="spacer-10"></div>
                                        <div class="text-preview">Preview theme</div>

                                        <?php if ($i >= 2): ?>
                                            <div class="spacer-10"></div>

                                            <div id="wmp_waitlist_app<?php echo $i;?>_container">
                                                <div id="wmp_waitlist_action">
                                                    <a href="<?php echo $premium_link;?>" target="_blank" class="btn orange smaller">Available in PRO</a>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                            <div class="name"><?php echo $arr_themes_names[$i];?></div>
                        </div>
                    <?php endfor;?>
                </div>
            </div>
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
            window.WMPJSInterface.add("UI_previewthemesgallery","WMP_THEMES_GALLERY",{'DOMDoc':window.document, 'baseThemeUrl': '<?php echo plugins_url()."/".WMP_DOMAIN.'/frontend/themes/app1';?>'}, window);
        });
    }
</script>
