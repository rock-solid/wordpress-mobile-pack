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
            
            <div class="details offline">
                <h2 class="title">Coming Soon</h2>
           		<div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                
            	<div class="offline-mode"> 
                 	<p>Have your mobile web application available in offline mode?</p>
                    <div class="spacer-20"></div>
                 	<!-- add radio buttons -->
                    <input type="radio" name="offline" id="on" disabled="disabled" /><label for="on">ON</label>
                    <div class="spacer-10"></div>
                    <input type="radio" name="offline" id="off" disabled="disabled" checked="checked" /><label for="off">OFF</label>
                </div>
                
                <?php
                    $joined_settings_waitlist = false;
                     
                    $joined_waitlists = unserialize(WMobilePack::wmp_get_setting('joined_waitlists'));
                    
                    if ($joined_waitlists != '' && in_array('settings', $joined_waitlists))
                        $joined_settings_waitlist = true;
                ?>
                
                <div class="waitlist" id="wmp_waitlist_container">
                
                    <div class="spacer-20"></div>
                    <div class="spacer-20"></div>
                    
                    <?php if ($joined_settings_waitlist == false):?>
                        <div id="wmp_waitlist_action">
                            <a href="javascript:void(0);" id="wmp_waitlist_display_btn" class="btn blue smaller">Join Waitlist</a>
                            <div class="spacer-0"></div>
                            <p>and get notified when available</p>
                        </div>
                    
                        <form name="wmp_waitlist_form" id="wmp_waitlist_form" action="" method="post" style="display: none;">    
                            <div class="info">
                        	   <input name="wmp_waitlist_emailaddress" id="wmp_waitlist_emailaddress" type="text" placeholder="your email" class="small" value="<?php echo get_option( 'admin_email' );?>" />
                               <a href="javascript: void(0);" id="wmp_waitlist_send_btn" class="btn blue smallest">Ok</a>
                               <div class="spacer-5"></div>
                               <div class="field-message error" id="error_emailaddress_container"></div>
                        	   <div class="spacer-15"></div>
                    	   </div>
                        </form>
                    <?php endif;?>
                    
                    <div id="wmp_waitlist_added" class="added" style="display: <?php echo $joined_settings_waitlist ? 'block' : 'none'?>;">
                        <div class="switcher blue">
                        	<div class="msg">ADDED TO WAITLIST</div>
                            <div class="check"></div>
                        </div>
                        <div class="spacer-15"></div>
                	</div>
                </div>
                <div class="spacer-5"></div>
                <div class="grey-line"></div>
                <div class="spacer-20"></div> 
            </div>
            
        </div>
    
        <div class="right-side">
        
            <!-- add feedback form -->
            <?php include_once('sections/wmp-feedback.php'); ?>
        </div>
	</div>
</div>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            window.WMPJSInterface.add("UI_editdisplay","WMP_EDIT_DISPLAY",{'DOMDoc':window.document}, window);
            
            <?php if ($joined_settings_waitlist == false):?>
            
                window.WMPJSInterface.add("UI_joinwaitlist",
                    "WMP_WAITLIST",
                    {
                        'DOMDoc':       window.document,
                        'container' :   window.document.getElementById('wmp_waitlist_container'),
                        'submitURL' :   '<?php echo WMP_WAITLIST_PATH;?>',
                        'listType' :    'settings'
                    }, 
                    window
                );
            <?php endif;?>
        });
    }
</script>

