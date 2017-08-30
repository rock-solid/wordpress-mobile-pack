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
	<div class="settings">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>

            <div class="details">
                <div class="display-mode">
                    <h2 class="title">App Settings</h2>
                    <div class="spacer-20"></div>
                    <form name="wmp_editsettings_form" id="wmp_editsettings_form" class="left" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_app" method="post" enctype="multipart/form-data">

                        <p>Choose display mode:</p>
                        <div class="spacer-10"></div>

                        <?php
                            $display_mode = WMobilePack_Options::get_setting('display_mode');
                            if ($display_mode == '')
                                $display_mode = 'normal';
                        ?>

                        <!-- add radio buttons -->
                        <input type="radio" name="wmp_editsettings_displaymode" id="wmp_editsettings_displaymode_normal" value="normal" <?php if ($display_mode == "normal") echo "checked" ;?> /><label for="wmp_editsettings_displaymode_normal"><strong>Normal</strong> (all mobile visitors)</label>
                        <div class="spacer-10"></div>

                        <input type="radio" name="wmp_editsettings_displaymode" id="wmp_editsettings_displaymode_preview" value="preview" <?php if ($display_mode == "preview") echo "checked" ;?> /><label for="wmp_editsettings_displaymode_preview"><strong>Preview</strong> (logged in administrators)</label>
                        <div class="spacer-10"></div>

                        <input type="radio" name="wmp_editsettings_displaymode" id="wmp_editsettings_displaymode_disabled" value="disabled" <?php if ($display_mode == "disabled") echo "checked" ;?> /><label for="wmp_editsettings_modedisplay_disabled"><strong>Disabled</strong> (hidden for all)</label>
                		<div class="spacer-30"></div>

                        <p>Google Analytics Id:</p>
                        <div class="spacer-10"></div>
                        <input type="text" name="wmp_editsettings_ganalyticsid" id="wmp_editsettings_ganalyticsid" placeholder="UA-000000-01" class="small indent" value="<?php echo WMobilePack_Options::get_setting('google_analytics_id');?>" />
                        <div class="field-message error" id="error_ganalyticsid_container"></div>
                        <div class="spacer-30"></div>

						<p>Tablets opt-in:</p>
						<div class="spacer-10"></div>
						<?php $enable_tablets = WMobilePack_Options::get_setting('enable_tablets'); ?>

						<input type="hidden" name="wmp_editsettings_enable_tablets" id="wmp_editsettings_enable_tablets" value="<?php echo $enable_tablets;?>" />
						<input type="checkbox" name="wmp_enable_tablets_check" id="wmp_enable_tablets_check" value="0" <?php if ($enable_tablets == 1) echo "checked" ;?> />
						<label for ="wmp_enable_tablets_check">Display on iPad and Android tablets</label>

						<div class="spacer-30"></div>

                        <p>Menu options:</p>
                        <div class="spacer-10"></div>
                        <?php $display_website_link = WMobilePack_Options::get_setting('display_website_link'); ?>

                        <input type="hidden" name="wmp_editsettings_displaywebsitelink" id="wmp_editsettings_displaywebsitelink" value="<?php echo $display_website_link;?>" />
                        <input type="checkbox" name="wmp_displaywebsitelink_check" id="wmp_displaywebsitelink_check" value="1" <?php if ($display_website_link == 1) echo "checked" ;?> /><label for="wmp_displaywebsitelink_check">Display "Visit website" link</label>

                        <div class="spacer-30"></div>

                        <?php
                            $posts_per_page = WMobilePack_Options::get_setting('posts_per_page');

							// Check if the theme has a posts_per_page setting
                            $theme_config = WMobilePack_Themes_Config::get_theme_config();
                            $allow_posts_per_page = $theme_config !== false && $theme_config['posts_per_page'] == 1;

                            if ($allow_posts_per_page):
                        ?>

                            <p>Choose how posts are displayed:</p>
                            <div class="spacer-10"></div>

                            <!-- add radio buttons -->
                            <input type="radio" name="wmp_editsettings_postsperpage" id="wmp_editsettings_postsperpage_auto" value="auto" <?php if ($posts_per_page == "auto") echo "checked" ;?> /><label for="wmp_editsettings_postsperpage_auto"><strong>Auto</strong> (1 or 2 posts per page)</label>
                            <div class="spacer-10"></div>

                            <input type="radio" name="wmp_editsettings_postsperpage" id="wmp_editsettings_postsperpage_single" value="single" <?php if ($posts_per_page == "single") echo "checked" ;?> /><label for="wmp_editsettings_postsperpage_single">One post per page</label>
                            <div class="spacer-10"></div>

                            <input type="radio" name="wmp_editsettings_postsperpage" id="wmp_editsettings_postsperpage_double" value="double" <?php if ($posts_per_page == "double") echo "checked" ;?> /><label for="wmp_editsettings_postsperpage_double">Two posts per page</label>

                            <div class="spacer-20"></div>

                        <?php else: // otherwise, use the current value ?>
                            <input type="hidden" name="wmp_editsettings_postsperpage" id="wmp_editsettings_postsperpage" value="<?php echo $posts_per_page;?>" />
                        <?php endif;?>

                        <a href="javascript:void(0)" id="wmp_editsettings_send_btn" class="btn green smaller">Save</a>
                    </form>
					<div class="notices-container left">
						<div class="notice notice-left right" style="margin: 0px 0 15px 0;">
							<span>
								Edit the <strong>Display Mode</strong> of your app to enable/disable it for your mobile readers. The <strong>Preview mode</strong> lets you edit your app without it being visible to anyone else.<br/><br/><br/>
								By adding your <strong>Google Analytics ID</strong>, you will be able to track the mobile web application's visitors directly in your Google Analytics account.
							</span>
						</div>
						<div class="notice notice-left right" style="margin: 25px 0 15px 0;">
							<span>
								Clear mobile browser cache before testing tablets settings.
							</span>
						</div>
						<?php if ($allow_posts_per_page):?>
							<div class="notice notice-left right" style="margin: 70px 0 15px 0;">
								<span>
									The '<strong>Two posts per page</strong>' option will display posts in groups of two, as long as the categories have an even number of posts. If a category has an odd number of posts, the last card will contain a single post.
								</span>
							</div>
						<?php endif;?>
					</div>
                </div>
                <div class="spacer-0"></div>
            </div>
			<div class="spacer-15"></div>

			<div class="details">
                <div class="display-mode">
                    <h2 class="title">Enable Facebook, Twitter, Google+</h2>
                    <div class="spacer-20"></div>
                    <form name="wmp_socialmedia_form" id="wmp_socialmedia_form" class="left" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_save" method="post" style="min-width: 300px;">

                        <?php
                            foreach (array('facebook', 'twitter', 'google') as $social_network):

                                $is_enabled = WMobilePack_Options::get_setting('enable_'.$social_network);
                        ?>

                            <input type="hidden" name="wmp_option_enable_<?php echo $social_network;?>" id="wmp_option_enable_<?php echo $social_network;?>" value="<?php echo $is_enabled;?>" />
                            <input type="checkbox" name="wmp_socialmedia_<?php echo $social_network;?>_check" id="wmp_socialmedia_<?php echo $social_network;?>_check" value="1" <?php if ($is_enabled == 1) echo "checked" ;?> />
                            <label for="wmp_socialmedia_<?php echo $social_network;?>_check">

                                    <?php if ($social_network == 'facebook' || $social_network == 'twitter'):?>
                                        Enable <?php echo ucfirst($social_network);?> sharing
                                    <?php else:?>
                                        Enable Google+ sharing
                                    <?php endif;?>

                            </label>
                            <div class="spacer-10"></div>

                        <?php endforeach;?>

                        <div class="spacer-10"></div>
                        <a href="javascript:void(0)" id="wmp_socialmedia_send_btn" class="btn green smaller">Save</a>
                    </form>
                </div>
                <div class="spacer-0"></div>
            </div>
            <div class="spacer-15"></div>
            <div class="details">
                <div class="display-mode">
                    <h2 class="title">Language Settings</h2>
                    <div class="spacer-20"></div>
                    <p>Wordpress Mobile Pack will automatically translate your mobile web app in one of the supported languages: Bosnian, Chinese (zh_CN), Dutch, English, French, German, Hungarian, Italian, Polish, Portuguese (Brazil), Romanian, Spanish or Swedish. This is done based on your Wordpress settings and doesn't require additional changes from the plugin. A big thanks to all of our <a href="https://wordpress.org/plugins/wordpress-mobile-pack/other_notes/" target="_blank">contributors</a>.</p>
                    <div class="spacer-10"></div>
                    <p>However, if you wish to add another language or change the labels for your current one, you can do so by editing the language files located in <strong><?php echo WMP_PLUGIN_PATH."frontend/locales";?></strong>. To ensure your translation file will not be overwritten by future updates, please send it to our <a href="mailto:<?php echo WMP_FEEDBACK_EMAIL;?>">support team</a>.</p>
                    <div class="spacer-10"></div>
                </div>
                <div class="spacer-0"></div>
            </div>

            <div class="spacer-15"></div>

			<a name="verifyapikey"></a>
            <div class="details">
                <div class="display-mode">
                    <h2 class="title">Connect with Appticles</h2>
                    <div class="spacer-20"></div>

                    <p>Looking for VIP services? Check out <a href="https://www.appticles.com?wp_mobile_pack=settings" target="_blank">Appticles.com</a>, a multi-channel mobile publishing platform that empowers digital publishers to grow their mobile audience. </p>
                    <div class="spacer-20"></div>
                    <form name="wmp_connect_form" id="wmp_connect_form" class="left" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_premium_save" method="post">
                        <p>API Key:</p>
                        <div class="spacer-10"></div>
                        <input type="text" name="wmp_connect_apikey" id="wmp_connect_apikey" class="small indent" value="" />
                        <div class="field-message error" id="error_apikey_container"></div>
                        <div class="spacer-20"></div>
                        <a href="javascript:void(0)" id="wmp_connect_send_btn" class="btn green smaller">Save</a>
                    </form>
					<div class="notices-container left">
						<div class="notice notice-left right" style="margin: 0px 0 15px 0; top:-10px;">
							<span>
								Once your Appticles API key is validated, your WP Mobile Pack admin area will be transformed and you will be able to change your mobile web application settings from the Appticles.com dashboard.
							</span>
						</div>
					</div>
                </div>
                <div class="spacer-0"></div>
            </div>

            <div class="spacer-15"></div>

            <div class="details">
            	<div class="display-mode">
                 	<h2 class="title">Tracking</h2>
                    <div class="spacer-20"></div>

                    <form name="wmp_allowtracking_form" id="wmp_allowtracking_form" class="left" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_save" method="post">
                        <?php $enabled_tracking = WMobilePack_Options::get_setting('allow_tracking'); ?>

                        <input type="hidden" name="wmp_option_allow_tracking" id="wmp_option_allow_tracking" value="<?php echo $enabled_tracking;?>" />
                        <input type="checkbox" name="wmp_allowtracking_check" id="wmp_allowtracking_check" value="1" <?php if ($enabled_tracking == 1) echo "checked" ;?> />
                        <label for="wmp_allowtracking_check"><strong>Allow tracking of this WordPress install's anonymous data.</strong></label>
                        <div class="spacer-10"></div>

                        <p style="padding-left: 25px;">To maintain this plugin as best as possible, we need to know what we're dealing with: what kinds of other plugins our users are using, what themes, etc. Please allow us to track that data from your install. It will not track any user details, so your security and privacy are safe with us.</p>
                        <div class="spacer-10"></div>
                        <a href="javascript:void(0)" id="wmp_allowtracking_send_btn" class="btn green smaller">Save</a>
                    </form>
                </div>
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

<?php
// check if we have a https connection
$is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
?>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            window.WMPJSInterface.add("UI_editappsettings","WMP_APP_SETTINGS",{'DOMDoc':window.document}, window);
			window.WMPJSInterface.add("UI_socialmedia","WMP_SOCIAL_MEDIA",{'DOMDoc':window.document}, window);
            window.WMPJSInterface.add("UI_connect",
                "WMP_CONNECT",
                {
                    'DOMDoc':       window.document,
                    'submitURL' :   '<?php echo $is_secure ? WMP_APPTICLES_CONNECT_SSL : WMP_APPTICLES_CONNECT;?>',
                    'redirectTo' :  '<?php echo admin_url('admin.php?page=wmp-options-premium');?>'
                },
                window
            );

            window.WMPJSInterface.add("UI_allowtracking","WMP_ALLOW_TRACKING",{'DOMDoc':window.document}, window);
        });
    }
</script>
