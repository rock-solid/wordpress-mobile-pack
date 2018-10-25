
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery("#save").click(function(e) {
        var jsonData2 = {};
        
        var fieldsetData = jQuery("#core-settings").serializeArray();
        var _this = this;
        jQuery.each(fieldsetData, function() {
            
            var custKey = jQuery('input[name="' + this.name + '"')[0].classList[0];
            // var deconc = this.name
            // console.log(_this.getAttribute('class'));
            jsonData2[custKey] = this.value || '';
        });
         console.log(jsonData2);
         var output2 = JSON.stringify(jQuery("#core-settings").serializeArray());
        jQuery.ajax(
        {

            url : "http://gt.localhost",
            type: "POST",
            data: output2,
            success: function(response) {
                alert("Settings saved.");
            }
        }); 
        e.preventDefault();
    }); 
});
</script>

<div id="wmpack-admin">
	<div class="spacer-60"></div>
    <!-- set title -->
    <h1>Publisher's Toolbox PWA <?php echo WMP_VERSION;?></h1>
	<div class="spacer-20"></div>
	<div class="settings">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>

            <div class="details">
                
                    <h2 class="title">App Settings</h2>
                    <div class="spacer-20"></div>
                    <form id="core-settings">
                        <div class="spacer-10"></div>
                        <label>Application Name</label>
                        <input class="appName" type="text" name="appName" id="appName" value="Gay Times" readonly/>
                        <div class="spacer-20"></div>
                        <label>Application Meta Description</label>
                        <input class="metaDescription" type="text" name="metaDescription" id="metaDescription" value="Gay Times Description" readonly/> 
                        <div class="spacer-20"></div>
                        <label>Host URL</label>
                        <input class="hostUrl" type="text" name="hostUrl" id="hostUrl" value="https://www.gaytimes.co.uk" readonly/>  
                		<div class="spacer-20"></div>
                        <label>Manifest URL</label>
                        <input class="manifestUrl" type="text" name="manifestUrl" id="manifestUrl" value="static/www.gaytimes.co.uk/manifest.json" readonly/>
                        <div class="spacer-20"></div>
                        <label>Date Format</label>
                        <select class="newsItemDateFormat" id="newsItemDateFormat">
                            <option value="dd-mm-yyyy">DD-MM-YYYY</option>
                            <option value="yyyy-mm-dd">YYYY-MM-DD</option>
                            <option value="dd-mmmm-yy">DD-MMMM-YY</option>
                            <option value="yy-mmmm-dd">YY-MMMM-DD</option>
                            <option value="dd-mmm-yyyy">DD-MMM-YYYY</option>
                            <option value="yyyy-mmm-dd">YYYY-MM-DD</option>
                        </select>
                        <div class="spacer-20"></div>
                        <label>Time Format</label>
                        <select class="newsItemTimeFormat" id="newsItemTimeFormat">
                            <option value="12h">12 Hours</option>
                            <option value="24h">24 Hours</option>
                        </select>    
						<div class="spacer-20"></div>
						<label>Default Feed Page Size (W x H)</label>
                        <select class="defaultFeedPageSize" id="defaultFeedPageSize">
                            <option value="313x420">313 x 420</option>
                            <option value="626x840">626 x 840</option>
                        </select> <!-- values taken from here: https://www.postplanner.com/ultimate-guide-to-facebook-dimensions-cheat-sheet/ -->   
						<div class="spacer-20"></div>
                        <label>Google Tag Manager ID</label>
                        <input class="GTMID" type="text" name="GTMID" id="GTMID" value="GTM-XXXXX" />
                        <div class="spacer-20"></div>
                        <label>Google Analytics Tracking Code</label>
                        <input class="GATrackingCode" type="text" name="GATrackingCode" value="UA-000000-01" />
                        <div class="spacer-30"></div>
                        <div class="submit"><input type="button" id="save" class="save" value="Save Settings"/></div>   
                         </form>   
                            <div class="spacer-10"></div>

                            
                            <div class="spacer-10"></div>

                            
                            <div class="spacer-10"></div>

                           

                            <div class="spacer-20"></div>

                       
                  
                <div class="spacer-0"></div>
           
			<div class="spacer-15"></div>
</form>
			<div class="details">
                
                    <div class="spacer-20"></div>
                    
                            <div class="spacer-10"></div>

                       

                        <div class="spacer-10"></div>
                       
                <div class="spacer-0"></div>
          
            <div class="spacer-15"></div>
            <!--<div class="details">
                <div class="display-mode">
                    <h2 class="title">Language Settings</h2>
                    <div class="spacer-20"></div>
                    <p>Wordpress Mobile Pack will automatically translate your mobile web app in one of the supported languages: Bosnian, Chinese (zh_CN), Dutch, English, French, German, Hungarian, Italian, Polish, Portuguese (Brazil), Romanian, Spanish or Swedish. This is done based on your Wordpress settings and doesn't require additional changes from the plugin. A big thanks to all of our <a href="https://wordpress.org/plugins/wordpress-mobile-pack/other_notes/" target="_blank">contributors</a>.</p>
                    <div class="spacer-10"></div>
                    <p>However, if you wish to add another language or change the labels for your current one, you can do so by editing the language files located in <strong><?php #echo WMP_PLUGIN_PATH."frontend/locales";?></strong>. To ensure your translation file will not be overwritten by future updates, please send it to our <a href="mailto:<?php #echo WMP_FEEDBACK_EMAIL;?>">support team</a>.</p>
                    <div class="spacer-10"></div>
                </div>
                <div class="spacer-0"></div>
            </div>-->

            <div class="spacer-15"></div>

			<!--<div class="details">
            	<div class="display-mode">
                 	<h2 class="title">Tracking</h2>
                    <div class="spacer-20"></div>

                    <form name="wmp_allowtracking_form" id="wmp_allowtracking_form" class="left" action="<?php #echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_save" method="post">
                        <?php #$enabled_tracking = WMobilePack_Options::get_setting('allow_tracking'); ?>

                        <input type="hidden" name="wmp_option_allow_tracking" id="wmp_option_allow_tracking" value="<?php #echo $enabled_tracking;?>" />
                        <input type="checkbox" name="wmp_allowtracking_check" id="wmp_allowtracking_check" value="1" <?php #if ($enabled_tracking == 1) echo "checked" ;?> />
                        <label for="wmp_allowtracking_check"><strong>Allow tracking of this WordPress install's anonymous data.</strong></label>
                        <div class="spacer-10"></div>

                        <p style="padding-left: 25px;">To maintain this plugin as best as possible, we need to know what we're dealing with: what kinds of other plugins our users are using, what themes, etc. Please allow us to track that data from your install. It will not track any user details, so your security and privacy are safe with us.</p>
                        <div class="spacer-10"></div>
                        <a href="javascript:void(0)" id="wmp_allowtracking_send_btn" class="btn green smaller">Save</a>
                    </form>
                </div>
                 <div class="spacer-0"></div>
            </div>
        </div>-->

       
            <!-- waitlist form -->
            <?php #include_once(WMP_PLUGIN_PATH.'admin/sections/waitlist.php'); ?>

            <!-- add feedback form -->
            <?php #include_once(WMP_PLUGIN_PATH.'admin/sections/feedback.php'); ?>
        </div>
	</div>
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
            window.WMPJSInterface.add("UI_allowtracking","WMP_ALLOW_TRACKING",{'DOMDoc':window.document}, window);
        });
    }
</script>
