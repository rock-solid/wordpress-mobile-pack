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
    <h1><?php echo WMP_PLUGIN_NAME; ?></h1>
	<div class="spacer-20"></div>
	<div class="look-and-feel">
        <div class="left-side">
        
            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>
            
            <!-- add content form -->
            <div class="details">
                <div class="spacer-10"></div>
                <p>Currently there's only one theme available, but we're working on others so you're welcome to join the waitlist. You can choose from the below color schemes & fonts, add your logo & app icon. The default theme comes with 6 abstract covers that are randomly displayed on the loading screen to give your app a magazine flavor.</p>
                <div class="spacer-20"></div>
            </div>
            <div class="spacer-10"></div>
            
            <div class="details theming">
                <h2 class="title">Choose Your Theme</h2>
                <div class="spacer_15"></div>
                <div class="spacer-15"></div>
                <?php
                    $joined_business_waitlist = false;
                    $joined_lifestyle_waitlist = false;
                     
                    $joined_waitlists = unserialize(WMobilePack::wmp_get_setting('joined_waitlists'));
                    
                    if ($joined_waitlists != '' && in_array('businesstheme', $joined_waitlists))
                        $joined_business_waitlist = true;
                    
                    if ($joined_waitlists != '' && in_array('lifestyletheme', $joined_waitlists))
                        $joined_lifestyle_waitlist = true;
                ?>
                
                <div class="themes">
                	<div class="theme">
                    	<div class="corner relative active">
                            <div class="indicator"></div>
                        </div>
                        <div class="image" style="background:url(<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/theme-3.jpg);">
                        	<div class="relative">
                            	<div class="overlay">
                                	<div class="spacer-70"></div>
                            		<div class="preview" id="wmp_themes_gallery_base"></div>
                                    <div class="spacer-10"></div>
                                    <div class="text">Preview theme</div>
                            	</div>
                            </div>
                        </div>
                        <div class="name">Blogish</div>
                        <div class="content">
                            Content type
                        	<div class="wordpress-icon"></div>
                        </div>
                    </div>
                    
                    <div class="theme waitlist <?php if ($joined_business_waitlist) echo 'added' ;?>">
                    	<div class="corner relative inactive">
                            <div class="indicator"></div>
                        </div>
                        <div class="image" style="background:url(<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/theme-1.jpg);">
                        	<div class="relative">
                            	<div class="overlay">
                                	<div class="spacer-30"></div>
                            		<div class="preview" id="wmp_themes_gallery_business"></div>
                                    <div class="spacer-10"></div>
                                    <div class="text">Preview theme</div>
                                    <div class="spacer-5"></div>
                                    
                                    <div id="wmp_waitlist_business_container">
                                    
                                        <?php if ($joined_business_waitlist == false):?>
                                        
                                            <div id="wmp_waitlist_action">
                                                <a href="javascript:void(0);" id="wmp_waitlist_display_btn" class="btn blue smaller">Join Waitlist</a>
                                                <div class="text">
                                                	<em>and get notified when<br/> available</em>	
                                                </div>
                                            </div>
                                        
                                            <form name="wmp_waitlist_form" id="wmp_waitlist_form" action="" method="post" style="display: none;">
                                                <div class="info">
                                            	   <input name="wmp_waitlist_emailaddress" id="wmp_waitlist_emailaddress" type="text" placeholder="your email" class="smaller" value="<?php echo get_option( 'admin_email' );?>" />
                                                   <a href="javascript: void(0);" id="wmp_waitlist_send_btn" class="btn blue smallest">Ok</a>
                                                   <div class="spacer-5"></div>
                                                   <div class="field-message error" id="error_emailaddress_container"></div>
                                        	   </div>
                                            </form>
                                        <?php endif;?>
                                    
                                        <div id="wmp_waitlist_added" style="display: <?php echo $joined_business_waitlist ? 'block' : 'none'?>;">
                                        
                                            <div class="spacer-15"></div>
                                            <div class="text">
        										<span>ADDED TO<br/>WAITLIST</span>                                    	
                                            </div>
                                    	</div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="name">Theme #2</div>
                        <div class="content">Content type
                        	<div class="facebook-icon"></div>
                            <div class="twitter-icon"></div>
                            <div class="rss-icon"></div>
                        </div>
                    </div>
                    <div class="theme waitlist <?php if ($joined_lifestyle_waitlist) echo 'added' ;?>">
                    	<div class="corner relative inactive">
                            <div class="indicator"></div>
                        </div>
                        <div class="image" style="background:url(<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/theme-2.jpg);">
                        	<div class="relative">
                            	<div class="overlay">
                                    <div class="spacer-30"></div>
                            		<div class="preview" id="wmp_themes_gallery_lifestyle"></div>
                                    <div class="spacer-10"></div>
                                    <div class="text">Preview theme</div>
                                    <div class="spacer-5"></div>
                                    
                                	<div id="wmp_waitlist_lifestyle_container">
                                    
                                        <?php if ($joined_lifestyle_waitlist == false):?>
                                        
                                            <div id="wmp_waitlist_action">
                                                <a href="javascript:void(0);" id="wmp_waitlist_display_btn" class="btn blue smaller">Join Waitlist</a>
                                                <div class="text">
                                                	<em>and get notified when<br/> available</em>
                                                </div>
                                            </div>
                                        
                                            <form name="wmp_waitlist_form" id="wmp_waitlist_form" action="" method="post" style="display: none;">
                                                <div class="info">
                                            	   <input name="wmp_waitlist_emailaddress" id="wmp_waitlist_emailaddress" type="text" placeholder="your email" class="smaller" value="<?php echo get_option( 'admin_email' );?>" />
                                                   <a href="javascript: void(0);" id="wmp_waitlist_send_btn" class="btn blue smallest">Ok</a>
                                                   <div class="spacer-5"></div>
                                                   <div class="field-message error" id="error_emailaddress_container"></div>
                                        	   </div>
                                            </form>
                                        <?php endif;?>
                                    
                                        <div id="wmp_waitlist_added" style="display: <?php echo $joined_lifestyle_waitlist ? 'block' : 'none'?>;">
                                        
                                            <div class="spacer-15"></div>
                                            <div class="text">
        										<span>ADDED TO<br/>WAITLIST</span>                                    	
                                            </div>
                                    	</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="name">Theme #3</div>
                        <div class="content">Content type
                        	<div class="facebook-icon"></div>
                            <div class="twitter-icon"></div>
                            <div class="rss-icon"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="spacer-10"></div>
            <div class="details">
                <h2 class="title">Customize Color Schemes and Fonts</h2>
                <div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <div class="spacer-20"></div>
                
                <form name="wmp_edittheme_form" id="wmp_edittheme_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_save" method="post" enctype="multipart/form-data">
                
                    <div class="color-schemes">
                        <p class="section-header">Color scheme</p>
                        <div class="spacer-20"></div>
                        <?php 
                            $color_scheme = WMobilePack::wmp_get_setting('color_scheme');
                            if ($color_scheme == '')
                                $color_scheme = 1;
                        ?>
                        
                        <!-- add label -->
                        <div class="colors description">
                        	<div class="color-" title="Headlines and texts">1</div>
                            <div class="color-" title="Category label">2</div>
                            <div class="color-" title="Forms">3</div>
                            <div class="color-" title="Buttons">4</div>
                            <div class="color-" title="Article header & detail background">5</div>
                        </div>
                        <div class="spacer-15"></div>
                        <!-- add radio buttons -->
                        <input type="radio" name="wmp_edittheme_colorscheme" id="wmp_edittheme_colorscheme" value="1" <?php if ($color_scheme == 1) echo "checked";?> />
                        <div class="colors">
                        	<div class="color-1-1" title="Headlines and texts"></div>
                            <div class="color-1-2" title="Category label"></div>
                            <div class="color-1-3" title="Forms"></div>
                            <div class="color-1-4" title="Buttons"></div>
                            <div class="color-1-5" title="Article header & detail background"></div>
                        </div>
                        <div class="spacer-20"></div>
                        <input type="radio" name="wmp_edittheme_colorscheme" id="wmp_edittheme_colorscheme" value="2" <?php if ($color_scheme == 2) echo "checked";?> />
                        <div class="colors">
                        	<div class="color-2-1" title="Headlines and texts"></div>
                            <div class="color-2-2" title="Category label"></div>
                            <div class="color-2-3" title="Forms"></div>
                            <div class="color-2-4" title="Buttons"></div>
                            <div class="color-2-5" title="Article header & detail background"></div>
                        </div>
                        <div class="spacer-20"></div>
                        <input type="radio" name="wmp_edittheme_colorscheme" id="wmp_edittheme_colorscheme" value="3" <?php if ($color_scheme == 3) echo "checked";?> />
                        <div class="colors">
                        	<div class="color-3-1" title="Headlines and texts"></div>
                            <div class="color-3-2" title="Category label"></div>
                            <div class="color-3-3" title="Forms"></div>
                            <div class="color-3-4" title="Buttons"></div>
                            <div class="color-3-5" title="Article header & detail background"></div>
                        </div>
                        <div class="spacer-30"></div>
                        <!-- start notice -->
                        <div class="notice notice-top left" style="width: 345px;">
                            <span>
                                The above color scheme will impact the following sections within the mobile web application:<br/><br/>
			
                                1.&nbsp;Headlines and texts<br/>
                                2.&nbsp;Category label<br/>
                                3.&nbsp;Forms<br/>
                                4.&nbsp;Buttons<br/>
                                5.&nbsp;Article header & detail background<br/>
                            </span>
                        </div>
                        <div class="spacer-10"></div>
                    </div>
                    
                    <div class="font-chooser">
                        <p class="section-header">Fonts</p>
                        <div class="spacer-20"></div>
                        
                        <?php 
                        
                            $enable_custom_selects = false;
                            
                            $blog_version = floatval(get_bloginfo('version')); 
                            
                            if ($blog_version >= WMobilePack::$wmp_customselect_enable)
                                $enable_custom_selects = true;
                        ?>
                        
                        <!-- add radio buttons -->
                        <?php 
                            $font_headlines = WMobilePack::wmp_get_setting('font_headlines');
                            if ($font_headlines == '')
                                $font_headlines = WMobilePack::$wmp_allowed_fonts[0];
                        ?>
                        
                        <label for="wmp_edittheme_fontheadlines">Headlines</label>
                        
                        <select name="wmp_edittheme_fontheadlines" id="wmp_edittheme_fontheadlines">
                        
                            <?php 
                                foreach (WMobilePack::$wmp_allowed_fonts as $font_family):
                            
                                    if ($enable_custom_selects):    
                            ?>
                                        <option value="<?php echo $font_family;?>" data-text='<span style="font-family:<?php echo str_replace(" ", "", $font_family);?>"><?php echo $font_family;?></span>' <?php if ($font_headlines == $font_family) echo "selected";?>></option>
                                        
                                    <?php else:?>
                                    
                                        <option value="<?php echo $font_family;?>" <?php if ($font_headlines == $font_family) echo "selected";?>><?php echo $font_family;?></option>
                            <?php   
                                    endif;                                        
                                endforeach;
                            ?>
                        </select>
                                                
                        <div class="spacer-10"></div>
                        
                        <?php 
                            $font_subtitles = WMobilePack::wmp_get_setting('font_subtitles');
                            if ($font_subtitles == '')
                                $font_subtitles = WMobilePack::$wmp_allowed_fonts[0];
                        ?>
                        
                        <label for="wmp_edittheme_fontsubtitles">Subtitles</label>
                        <select name="wmp_edittheme_fontsubtitles" id="wmp_edittheme_fontsubtitles">
                            <?php 
                                foreach (WMobilePack::$wmp_allowed_fonts as $font_family):
                            
                                    if ($enable_custom_selects):    
                            ?>
                                        <option value="<?php echo $font_family;?>" data-text='<span style="font-family:<?php echo str_replace(" ", "", $font_family);?>"><?php echo $font_family;?></span>' <?php if ($font_subtitles == $font_family) echo "selected";?>></option>
                                        
                                    <?php else:?>
                                    
                                        <option value="<?php echo $font_family;?>" <?php if ($font_subtitles == $font_family) echo "selected";?>><?php echo $font_family;?></option>
                            <?php   
                                    endif;                                        
                                endforeach;
                            ?>
                        </select>
                        <div class="spacer-10"></div>
                        
                        <?php 
                            $font_paragraphs = WMobilePack::wmp_get_setting('font_paragraphs');
                            if ($font_paragraphs == '')
                                $font_paragraphs = WMobilePack::$wmp_allowed_fonts[0];
                        ?>
                        
                        <label for="wmp_edittheme_fontparagraphs">Paragraphs</label>
                        <select name="wmp_edittheme_fontparagraphs" id="wmp_edittheme_fontparagraphs">
                            <?php 
                                foreach (WMobilePack::$wmp_allowed_fonts as $font_family):
                            
                                    if ($enable_custom_selects):    
                            ?>
                                        <option value="<?php echo $font_family;?>" data-text='<span style="font-family:<?php echo str_replace(" ", "", $font_family);?>"><?php echo $font_family;?></span>' <?php if ($font_paragraphs == $font_family) echo "selected";?>></option>
                                        
                                    <?php else:?>
                                    
                                        <option value="<?php echo $font_family;?>" <?php if ($font_paragraphs == $font_family) echo "selected";?>><?php echo $font_family;?></option>
                            <?php   
                                    endif;                                        
                                endforeach;
                            ?>
                        </select>
                        <div class="spacer-20"></div>        
                    </div>
                </form>
                
                <div class="spacer-20"></div>
                <a href="javascript:void(0);" id="wmp_edittheme_send_btn" class="btn green smaller" >Save</a> 
            </div>
            <div class="spacer-15"></div>
            
            <div class="details branding">
            	
                <h2 class="title">Customize Your App's Logo and Icon</h2>
                <div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-20"></div>
                <p>You can also personalize your app by adding <strong>your own logo and icon</strong>. The logo will be displayed on the home page of your mobile web app, while the icon will be used when readers add your app to their homescreen.</p>
                <div class="spacer-20"></div>
                <div class="left">
                    <form name="wmp_editimages_form" id="wmp_editimages_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_editimages&type=upload" method="post" enctype="multipart/form-data">
                       
                        <?php
                            $logo_path = WMobilePack::wmp_get_setting('logo');
                            
                            if (!file_exists(WMP_FILES_UPLOADS_DIR.$logo_path))
                                $logo_path = '';    
                        ?>
    
                        <!-- upload logo field -->
                        <div class="wmp_editimages_uploadlogo" style="display: <?php echo $logo_path == '' ? 'block' : 'none';?>;">
                        
                            <label for="wmp_editimages_logo">Upload your app logo</label>
                            
                            <div class="custom-upload">
                            
                                <input type="file" id="wmp_editimages_logo" name="wmp_editimages_logo" />
                                <div class="fake-file">
                                    <input type="text" id="fakefilelogo" disabled="disabled" />
                                    <a href="#" class="btn grey smaller">Browse</a>
                                </div>
                                
                                
                                <a href="javascript:void(0)" id="wmp_editimages_logo_removenew" class="remove" style="display: none;"></a>
                            </div> 
                            
                            <!-- cancel upload logo button -->
                            <div class="wmp_editimages_changelogo_cancel cancel-link" style="display: none;">
                                <a href="javascript:void(0);" class="cancel">cancel</a>
                            </div>
                            <div class="field-message error" id="error_logo_container"></div>
                        
                        </div>
                        
                        <!-- logo image -->
                        <div class="wmp_editimages_logocontainer display-logo" style="display: <?php echo $logo_path != '' ? 'block' : 'none';?>;">
                        
                            <label for="branding_logo">App logo</label>
                            <div class="img" id="wmp_editimages_currentlogo" style="background:url(<?php echo WMP_FILES_UPLOADS_URL.$logo_path;?>); background-size:contain; background-repeat: no-repeat; background-position: center"></div>
                            
                            <!-- edit/delete logo links -->
                            <a href="javascript:void(0);" class="wmp_editimages_changelogo btn grey smaller edit">Change</a>
                            <a href="#" class="wmp_editimages_deletelogo smaller remove">remove</a>
                            
                        </div>
                                    
                        <div class="spacer-20"></div>
                        
                        <?php
                            $icon_path = WMobilePack::wmp_get_setting('icon');
                            
                            if (!file_exists(WMP_FILES_UPLOADS_DIR.$icon_path))
                                $icon_path = '';    
                        ?>
    
                        <!-- upload icon field -->
                        <div class="wmp_editimages_uploadicon" style="display: <?php echo $icon_path == '' ? 'block' : 'none';?>;">
                        
                            <label for="wmp_editimages_icon">Upload your app icon</label>
                            
                            <div class="custom-upload">
                            
                                <input type="file" id="wmp_editimages_icon" name="wmp_editimages_icon" />
                                <div class="fake-file">
                                    <input type="text" id="fakefileicon" disabled="disabled" />
                                    <a href="#" class="btn grey smaller">Browse</a>
                                </div>
                                
                                <a href="javascript:void(0)" id="wmp_editimages_icon_removenew" class="remove" style="display: none;"></a>
                            </div> 
                            <!-- cancel upload icon button -->
                            <div class="wmp_editimages_changeicon_cancel cancel-link" style="display: none;">
                                <a href="javascript:void(0);" class="cancel">cancel</a>    
                            </div>
                            <div class="field-message error" id="error_icon_container"></div>
                        
                        </div>
                        
                        <!-- icon image -->
                        <div class="wmp_editimages_iconcontainer display-icon" style="display: <?php echo $icon_path != '' ? 'block' : 'none';?>;;">
                        
                            <label for="branding_icon">App icon</label>
                            <img src="<?php echo WMP_FILES_UPLOADS_URL.$icon_path;?>" id="wmp_editimages_currenticon" />
                            
                            <!-- edit/delete icon links -->
                            <a href="javascript:void(0);" class="wmp_editimages_changeicon btn grey smaller edit">Change</a>
                            <a href="#" class="wmp_editimages_deleteicon smaller remove">remove</a>
                        </div>
                                    
                        <div class="spacer-20"></div>
                        
                        <a href="javascript:void(0);" id="wmp_editimages_send_btn" class="btn green smaller">Save</a>
    
                    </form>    
                </div>
                
                <div class="notice notice-left right" style="width: 280px;">
                    <span>
                        Add your logo in a .png format with a transparent background. This will be displayed on the cover of your app.<br /><br /> 
                        Your icon should be square with a recommended size of 256 x 256 px. This will be displayed when the app will be added to the homescreen.<br /><br /> 
                        The file size for uploaded images should not exceed 1MB.
                    </span>
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
    
<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            
            window.WMPJSInterface.add("UI_previewthemesgallery","WMP_THEMES_GALLERY",{'DOMDoc':window.document, 'baseThemeUrl': '<?php echo plugins_url()."/".WMP_DOMAIN.'/themes/'.WMobilePack::$wmp_basic_theme;?>'}, window);
            window.WMPJSInterface.add("UI_customizetheme","WMP_EDIT_THEME",{'DOMDoc':window.document, 'enableCustomSelects': <?php echo intval($enable_custom_selects);?>}, window);
            window.WMPJSInterface.add("UI_editimages","WMP_EDIT_IMAGES",{'DOMDoc':window.document}, window);
            
            <?php if ($joined_business_waitlist == false):?>
            
                window.WMPJSInterface.add("UI_joinwaitlist_business",
                    "WMP_WAITLIST",
                    {
                        'DOMDoc':       window.document,
                        'container' :   window.document.getElementById('wmp_waitlist_business_container'),
                        'submitURL' :   '<?php echo WMP_WAITLIST_PATH;?>',
                        'listType' :    'businesstheme'
                    }, 
                    window
                );
            <?php endif;?>
            
            <?php if ($joined_lifestyle_waitlist == false):?>
            
                window.WMPJSInterface.add("UI_joinwaitlist_lifestyle",
                    "WMP_WAITLIST",
                    {
                        'DOMDoc':       window.document,
                        'container' :   window.document.getElementById('wmp_waitlist_lifestyle_container'),
                        'submitURL' :   '<?php echo WMP_WAITLIST_PATH;?>',
                        'listType' :    'lifestyletheme'
                    }, 
                    window
                );
            <?php endif;?>
        });
    }
</script>