<script type="text/javascript">
    if (window.JSInterface && window.JSInterface != null){
        jQuery(document).ready(function(){
            
            JSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            JSInterface.init();
        });
    }
</script>
<div id="wmpack-admin">
	<div class="spacer-20"></div>
    <!-- set title -->
    <h1>Settings</h1>
	<div class="spacer-20"></div>
	<div class="settings">
        <div class="left-side">
            
            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>
            
            <!-- add settings -->
            <div class="details">
            	<div class="spacer-10"></div>
            	<p>Lorem ipsum dolor sit amet, nec accusamus assentior in, per ea probo percipit ullamcorper. An mel animal menandri vituperata. Vis an solet ocurreret, sit laudem semper perfecto ex, vix an nibh tacimates. Ne usu duis ignota oblique.</p>
            	<div class="spacer-20"></div>
            </div>
            <div class="spacer-15"></div>
            <div class="details offline">
            	<div class="offline-mode"> 
                 	<p>Offline mode:</p>
                    <div class="spacer-20"></div>
                 	<!-- add radio buttons -->
                    <input type="radio" name="offline" id="on" disabled="disabled" /><label for="on">On</label>
                    <div class="spacer-10"></div>
                    <input type="radio" name="offline" id="off" disabled="disabled" checked="checked" /><label for="off">Off</label>
                </div>
                <div class="waitlist">
                	<div class="spacer-20"></div>
                    <div class="spacer-20"></div>
                	 <a class="btn blue smaller" href="#">Join Waitlist</a>
                     <div class="spacer-0"></div>
                     <p>and get notified when available</p>
                </div>
                <div class="spacer-20"></div>
            </div>
            <div class="spacer-15"></div>
            <div class="details">
            	<div class="display-mode">
                 	<p>Choose display mode:</p>
                    <div class="spacer-20"></div>
                    <form name="display_form" action="" method="post">
                        <!-- add radio buttons -->
                        <input type="radio" name="display_mode" id="display_mode_normal" value="normal" /><label for="display_mode_normal"><strong>Normal</strong>&nbsp;(all mobile visitors)</label>
                        <div class="spacer-10"></div>
                        <input type="radio" name="display_mode" id="display_mode_preview" value="preview" /><label for="display_mode_preview"><strong>Preview</strong>&nbsp;(logged in administrators)</label>
                        <div class="spacer-10"></div>
                        <input type="radio" name="display_mode" id="display_mode_disabled" value="disabled" /><label for="display_mode_disabled"><strong>Disabled</strong>&nbsp;(hidden for all)</label>
                		<div class="spacer-20"></div>
                        <a class="btn green smaller" href="#">Save</a>
                    </form>
                </div>
                <div class="spacer-0"></div>
            </div>
            <div class="spacer-15"></div>
            
            <div class="details branding">
            	
                <h2 class="title">Customize Your App's Logo and Icon</h2>
                <div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <div class="spacer-20"></div>
                <div class="left">
                    <form name="editimages_form" id="editimages_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_editimages&type=upload" method="post" enctype="multipart/form-data">
                       
                        <?php
                            $logo_path = WMobilePack::wmp_get_setting('logo');
                            
                            if (!file_exists(WMP_FILES_UPLOADS_DIR.$logo_path))
                                $logo_path = '';    
                        ?>
    
                        <!-- upload logo field -->
                        <div class="editimages_uploadlogo" style="display: <?php echo $logo_path == '' ? 'block' : 'none';?>;">
                        
                            <label for="editimages_logo">Upload your app logo</label>
                            
                            <div class="custom-upload">
                            
                                <input type="file" id="editimages_logo" name="editimages_logo" />
                                <div class="fake-file">
                                    <input type="text" id="fakefilelogo" disabled="disabled" />
                                    <a href="#" class="btn grey smaller">Browse</a>
                                </div>
                                
                                <div class="error_container" id="error_logo_container"></div>
                                <a href="javascript:void(0)" id="editimages_logo_removenew" class="remove" style="display: none;"></a>
                            </div> 
                        
                        </div>
                        
                        <!-- logo image -->
                        <div class="editimages_logocontainer display-logo" style="display: <?php echo $logo_path != '' ? 'block' : 'none';?>;">
                        
                            <label for="branding_logo">App logo</label>
                            <div class="img" id="editimages_currentlogo" style="background:url(<?php echo WMP_FILES_UPLOADS_URL.$logo_path;?>); background-size:contain; background-repeat: no-repeat; background-position: center"></div>
                            
                            <!-- edit/delete logo links -->
                            <a href="javascript:void(0);" class="editimages_changelogo btn grey smaller edit">Change</a>
                            <a href="#" class="editimages_deletelogo smaller remove">remove</a>
                            
                        </div>
                        
                        <!-- cancel upload logo button -->
                        <div class="editimages_changelogo_cancel cancel-link" style="display: none;">
                            <a href="javascript:void(0);" class="cancel">cancel</a>
                        </div>
                                    
                        <div class="spacer-20"></div>
                        
                        <?php
                            $icon_path = WMobilePack::wmp_get_setting('icon');
                            
                            if (!file_exists(WMP_FILES_UPLOADS_DIR.$icon_path))
                                $icon_path = '';    
                        ?>
    
                        <!-- upload icon field -->
                        <div class="editimages_uploadicon" style="display: <?php echo $icon_path == '' ? 'block' : 'none';?>;">
                        
                            <label for="editimages_icon">Upload your app icon</label>
                            
                            <div class="custom-upload">
                            
                                <input type="file" id="editimages_icon" name="editimages_icon" />
                                <div class="fake-file">
                                    <input type="text" id="fakefileicon" disabled="disabled" />
                                    <a href="#" class="btn grey smaller">Browse</a>
                                </div>
                                
                                <div class="error_container" id="error_icon_container"></div>
                                <a href="javascript:void(0)" id="editimages_icon_removenew" class="remove" style="display: none;"></a>
                            </div> 
                        
                        </div>
                        
                        <!-- icon image -->
                        <div class="editimages_iconcontainer display-icon" style="display: <?php echo $icon_path != '' ? 'block' : 'none';?>;;">
                        
                            <label for="branding_icon">App icon</label>
                            <img src="<?php echo WMP_FILES_UPLOADS_URL.$icon_path;?>" id="editimages_currenticon" />
                            
                            <!-- edit/delete icon links -->
                            <a href="javascript:void(0);" class="editimages_changeicon btn grey smaller edit">Change</a>
                            <a href="#" class="editimages_deleteicon smaller remove">remove</a>
                        </div>
                        
                        <!-- cancel upload icon button -->
                        <div class="editimages_changeicon_cancel cancel-link" style="display: none;">
                            <a href="javascript:void(0);" class="cancel">cancel</a>    
                        </div>
                                    
                        <div class="spacer-20"></div>
                        
                        <a href="javascript:void(0);" id="editimages_send_btn" class="btn green smaller">Save</a>
    
                    </form>    
                </div>
                <div class="notice notice-left right" style="width: 230px;"> <span>Lorem ipsum dolor sit amet, nec accusamus assentior in, per ea probo percipit ullamcorper. An mel animal menandri vituperata. Vis an solet ocurreret, sit laudem semper perfecto ex, vix an nibh tacimates. Ne usu duis ignota oblique.</span> </div>
                <div class="spacer-0"></div>
            </div>
        </div>
    
        <div class="right-side">
        
            <!-- add news and updates -->
            <?php include_once('sections/wmp-news.php'); ?>
            <div class="spacer-15"></div>

			<!-- add newsletter box -->
            <?php include_once('sections/wmp-newsletter.php'); ?>
            <div class="spacer-15"></div>
            
            <!-- add feedback form -->
            <?php include_once('sections/wmp-feedback.php'); ?>
        </div>
	</div>
</div>

<script type="text/javascript">
    if (window.JSInterface && window.JSInterface != null){
        jQuery(document).ready(function(){
            window.JSInterface.add("UI_editimages","EDIT_IMAGES",{'DOMDoc':window.document}, window);
        });
    }
</script>

