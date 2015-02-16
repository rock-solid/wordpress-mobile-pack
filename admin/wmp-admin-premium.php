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
    <h1><?php echo WMP_PLUGIN_NAME;?> - Premium</h1>
    <div class="spacer-20"></div>
    
    <?php $page_content = WMobilePackAdmin::wmp_more_updates();?>
	<div class="whats-new">
        <div class="left-side">
            <div class="details go-premium-white">
                <div class="spacer-10"></div>
                
                <?php if (is_array($page_content) && !empty($page_content)): ?>
				    <?php if (array_key_exists('premium', $page_content) && array_key_exists('upgraded', $page_content)): ?>
                
                        <?php if (array_key_exists('showcase_image', $page_content['premium']) && array_key_exists('dashboard_url', $page_content['upgraded'])):?>
                            <div class="showcase">
                                <a href="<?php echo $page_content['upgraded']['dashboard_url'];?>" target="_blank">
                                    <img src="<?php echo $page_content['premium']['showcase_image'];?>" />
                                </a>
                                <div class="spacer-10"></div>
                            </div>
                        <?php endif;?>
                
                        <?php if (array_key_exists('text', $page_content['upgraded'])):?>
                            <p><?php echo $page_content['upgraded']['text'];?></p>
                        <?php endif;?>
                        
                        <div class="spacer-10"></div>
                        <?php if (array_key_exists('sync_text', $page_content['upgraded'])):?>
                            <p><?php echo $page_content['upgraded']['sync_text'];?></p>
                        <?php endif;?>
                         
                    <?php endif;?>
                <?php endif;?>   
                
                <div class="spacer-20"></div>
                <form name="wmp_disconnect_form" id="wmp_disconnect_form" class="left" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_premium_disconnect" method="post">
                    <input type="hidden" name="wmp_disconnect_apikey" id="wmp_disconnect_apikey" placeholder="api key*" class="small indent" value="<?php echo WMobilePack::wmp_get_setting('premium_api_key');?>" />
                    <p><strong>API Key</strong>: <?php echo WMobilePack::wmp_get_setting('premium_api_key');?></p>
                    <div class="spacer-10"></div>
                    <a class="btn blue smaller" href="javascript:void(0)" id="wmp_disconnect_send_btn">Disconnect</a>
                </form>
                <div class="spacer-10"></div>
                
            </div>
            <div class="spacer-10"></div>
            
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
            
            window.WMPJSInterface.add("UI_disconnect",
                "WMP_DISCONNECT",
                {
                    'DOMDoc':       window.document,
                    'submitURL' :   '<?php echo $is_secure ? WMP_APPTICLES_DISCONNECT_SSL : WMP_APPTICLES_DISCONNECT;?>',
					'redirectTo' :  '<?php echo admin_url('admin.php?page=wmp-options');?>'
                }, 
                window
            );
            
            window.WMPJSInterface.add("UI_allowtracking","WMP_ALLOW_TRACKING",{'DOMDoc':window.document}, window);
        });
    }
</script>