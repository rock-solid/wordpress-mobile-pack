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
    <h1><?php echo WMP_PLUGIN_NAME;?></h1>
	<div class="spacer-20"></div>
	<div class="settings">
        <div class="left-side">
            
            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>
            
            <!-- add settings -->
            <div class="details">
            	<div class="spacer-10"></div>
            	<p>
                    Edit the <strong>Display Mode</strong> of your app to enable/disable it for your mobile readers. The <strong>Preview mode</strong> lets you edit your app without it being visible to anyone else.
                
                </p>
            	<div class="spacer-20"></div>
            </div>
            <div class="spacer-15"></div>
            
            <div class="details">
            	<div class="display-mode">
                 	<p>Choose display mode:</p>
                    <div class="spacer-20"></div>
                    <form name="wmp_editsettings_form" id="wmp_editsettings_form" class="left" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_save" method="post" enctype="multipart/form-data">
                        <?php
                            $selected_value = WMobilePack::wmp_get_setting('display_mode');
                            if ($selected_value == '')
                                $selected_value = 'normal';
                        ?>

                        <!-- add radio buttons -->
                        <input type="radio" name="wmp_editsettings_displaymode" id="wmp_editsettings_displaymode_normal" value="normal" <?php if ($selected_value == "normal") echo "checked" ;?> /><label for="wmp_editsettings_displaymode_normal"><strong>Normal</strong> (all mobile visitors)</label>
                        <div class="spacer-10"></div>
                        
                        <input type="radio" name="wmp_editsettings_displaymode" id="wmp_editsettings_displaymode_preview" value="preview" <?php if ($selected_value == "preview") echo "checked" ;?> /><label for="wmp_editsettings_displaymode_preview"><strong>Preview</strong> (logged in administrators)</label>
                        <div class="spacer-10"></div>
                        
                        <input type="radio" name="wmp_editsettings_displaymode" id="wmp_editsettings_displaymode_disabled" value="disabled" <?php if ($selected_value == "disabled") echo "checked" ;?> /><label for="wmp_editsettings_modedisplay_disabled"><strong>Disabled</strong> (hidden for all)</label>
                		<div class="spacer-10"></div>
                        
                        <div class="field-message error" id="error_displaymode_container"></div>
                        <div class="spacer-20"></div>
                       
                        <p>Google Analytics Id:</p>
                        <div class="spacer-10"></div>
                        <input type="text" name="wmp_editsettings_ganalyticsid" id="wmp_editsettings_ganalyticsid" placeholder="UA-000000-01" class="small indent" value="<?php echo WMobilePack::wmp_get_setting('google_analytics_id');?>" />
                        <div class="field-message error" id="error_ganalyticsid_container"></div>
                        <div class="spacer-20"></div>
                        <a href="javascript:void(0)" id="wmp_editsettings_send_btn" class="btn green smaller">Save</a>
                     </form>
                     <div class="notice notice-left right" style="width: 465px; margin: 95px 0 15px 0;">
                        <span>
                            By adding your Google Analytics ID, you will be able to track the mobile web application's visitors directly in your Google Analytics account.
                        </span>
                     </div>
                </div>
                <div class="spacer-0"></div>
            </div>
            <div class="spacer-15"></div>
            <div class="details">
            	<div class="display-mode">
                 	<h2 class="title">Connect with Appticles</h2>
                    <div class="spacer-20"></div>
                    <?php
                        $premium_link = ''; 
                        
                        // Get premium link from the more json
                        $page_content = WMobilePackAdmin::wmp_more_updates();
                        
                        if  (is_array($page_content) && !empty($page_content)){
                            
                            if (array_key_exists('premium', $page_content)){
                                
                                if (array_key_exists('button_text', $page_content['premium']) && array_key_exists('button_link', $page_content['premium'])){
                                    
                                    $feed_url = '';
							
        							if (get_bloginfo('atom_url') != null && get_bloginfo('atom_url') != '')
        								$feed_url = '&feedurl='.urlencode(get_bloginfo('atom_url'));
        							elseif (get_bloginfo('rss2_url') != null && get_bloginfo('rss2_url') != '')
        								$feed_url = '&feedurl='.urlencode(get_bloginfo('rss2_url'));
                                    
                                    $premium_link = $page_content['premium']['button_link'].$feed_url.'&wmp_v=21';
                                }
                            }
                        } 
                    ?>
                    
                    <p>Extend your WP Mobile Pack with the <?php if ($premium_link):?><a href="<?php echo $premium_link;?>" target="_blank"><?php endif;?>Premium version<?php if ($premium_link):?></a><?php endif;?> by connecting with Appticles.com. Fill in the provided API Key to enable your Premium account.</p>
                    <div class="spacer-20"></div>
                    <form name="wmp_connect_form" id="wmp_connect_form" class="left" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_premium_save" method="post">
                        <input type="hidden" name="wmp_connect_settings" id="wmp_connect_settings"  value="<?php echo plugins_url()."/".WMP_DOMAIN.'/export/content.php?content=exportsettings';?>" />
                        <p>API Key:</p>
                        <div class="spacer-10"></div>
                        <input type="text" name="wmp_connect_apikey" id="wmp_connect_apikey" class="small indent" value="" />
                        <div class="field-message error" id="error_apikey_container"></div>
                        <div class="spacer-20"></div>
                        <a href="javascript:void(0)" id="wmp_connect_send_btn" class="btn green smaller">Save</a>
                     </form>
                     <div class="notice notice-left right" style="width: 465px; margin: 0px 0 15px 0; top:-10px;">
                        <span>
                            Once your API key is validated, your WP Mobile Pack admin area will be transformed and you will be able to change your mobile web application settings from the Appticles.com dashboard.
                        </span>
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
                        <?php $selected_value = WMobilePack::wmp_get_setting('allow_tracking'); ?>

                        <input type="hidden" name="wmp_allowtracking_box" id="wmp_allowtracking_box" value="<?php echo $selected_value;?>" />
                        <input type="checkbox" name="wmp_allowtracking_check" id="wmp_allowtracking_check" value="1" <?php if ($selected_value == 1) echo "checked" ;?> /><label for="wmp_allowtracking_check"><strong>Allow tracking of this WordPress install's anonymous data.</strong></label>
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
        	<!-- add waitlist form -->
            <?php include_once('sections/wmp-waitlist.php'); ?>
            <!-- add feedback form -->
            <?php include_once('sections/wmp-feedback.php'); ?>
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
            window.WMPJSInterface.add("UI_editdisplay","WMP_EDIT_DISPLAY",{'DOMDoc':window.document}, window);
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