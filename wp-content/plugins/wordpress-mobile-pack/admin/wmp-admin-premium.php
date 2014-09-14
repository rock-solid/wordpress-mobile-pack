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
            window.WMPJSInterface.add("UI_disconnect",
                    "WMP_DISCONNECT",
                    {
                        'DOMDoc':       window.document,
                        'submitURL' :   '<?php echo WMP_APPTICLES_DISCONNECT;?>',
						'redirectTo' :  '<?php echo admin_url('admin.php?page=wmp-options');?>'
                    }, 
                    window
                );
        });
    }
</script>