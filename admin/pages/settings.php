
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
    background: #0c4b7f;
    color: #ffffff;
    border: 1px solid #0c90c3;
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
                        <input class="appName" type="text" name="appName" id="appName" value=""/>
                        <div class="spacer-20"></div>
                        <label>Application Meta Description</label>
                        <input class="metaDescription" type="text" name="metaDescription" id="metaDescription" value=""/> 
                        <div class="spacer-20"></div>
                        <label>Host URL</label>
                        <input class="hostUrl" type="text" name="hostUrl" id="hostUrl" value=""/>  
                		<div class="spacer-20"></div>
                        <label>Manifest URL</label>
                        <input class="manifestUrl" type="text" name="manifestUrl" id="manifestUrl" value="" />
                        <div class="spacer-20"></div>
                        <label>Google Tag Manager ID</label>
                        <input class="GTMID" type="text" name="GTMID" id="GTMID" value="" />
                        <div class="spacer-20"></div>
                        <label>Google Analytics Tracking Code</label>
                        <input class="GATrackingCode" type="text" name="GATrackingCode" value="" />
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
