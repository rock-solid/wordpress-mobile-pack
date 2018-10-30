
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

<style>

.save {
    background: #9aca40;
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
                        <div class="spacer-20"></div>
                        <div class="submit"><input type="button" id="save" class="save" value="Save"/></div>   
                         </form>   

                       
        </div>
                  
                <div class="spacer-0"></div>
           
			    <div class="spacer-15"></div>

			<div class="details">
                <h2 class="title">Enable Social Media Sharing</h2>
                    <div class="spacer-20"></div>
                    <input type="checkbox" name="socialMedia" value="Facebook" checked="true"> Enable Facebook Sharing <br>
                    <div class="spacer-10"></div>
                    <input type="checkbox" name="socialMedia" value="Twitter" checked="true"> Enable Twitter Sharing <br>
                    <div class="spacer-10"></div>
                    <input type="checkbox" name="socialMedia" value="Google+" checked="true"> Enable Google+ Sharing <br>
                    <div class="spacer-10"></div>
                    <input type="checkbox" name="socialMedia" value="Whatsapp" checked="true"> Enable WhatsApp Sharing <br>

      </form>              
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
