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
                <p>Currently there's only one theme available, but we're working on others so you're welcome to join the waitlist. You can choose from the below color schemes & fonts, add your logo & app icon. The default theme comes with 6 abstract covers that are randomly displayed on the loading screen to give your app a magazine flavor. You can further personalize your mobile web application by uploading your own cover.</p>
                <div class="spacer-20"></div>
            </div>
            <div class="spacer-10"></div>
            
            <div class="details theming">
                <h2 class="title">Choose Your Theme</h2>
                <div class="spacer_15"></div>
                <div class="spacer-15"></div>
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
                            <span>Content type</span>
                        	<div class="wordpress-icon"></div>
                        </div>
                    </div>
                    
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

                    <div class="theme premium">
                    	<div class="corner relative">
                            <div class="indicator"></div>
                        </div>
                        <div class="image" style="background:url(<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/theme-1.jpg);">
                        	<div class="relative">
                            	<div class="overlay">
                                	<div class="spacer-30"></div>
                            		<div class="preview" id="wmp_themes_gallery_business"></div>
                                    <div class="spacer-20"></div>
                                    <div class="text">Preview theme</div>
                                    <div class="spacer-10"></div>
                                    
                                    <div id="wmp_waitlist_business_container">
                                    	<div id="wmp_waitlist_action">
                                            <?php if ($premium_link != ''):?>
                                                <a href="<?php echo $premium_link;?>" target="_blank" class="btn orange smaller">Go Premium</a>
                                            <?php endif;?>                                            
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="name">Theme #2</div>
                        <div class="content">
                        	<span>Content type</span>
                        	<div class="wordpress-icon"></div>
                            <div class="tumblr-icon"></div>
                        	<div class="rss-icon"></div>
                            <div class="facebook-icon"></div>
                            <div class="twitter-icon"></div>
                        </div>
                    </div>
                    <div class="theme premium">
                    	<div class="corner relative">
                            <div class="indicator"></div>
                        </div>
                        <div class="image" style="background:url(<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/theme-2.jpg);">
                        	<div class="relative">
                            	<div class="overlay">
                                    <div class="spacer-30"></div>
                            		<div class="preview" id="wmp_themes_gallery_lifestyle"></div>
                                    <div class="spacer-20"></div>
                                    <div class="text">Preview theme</div>
                                    <div class="spacer-10"></div>
                                    
                                	<div id="wmp_waitlist_lifestyle_container">
                                    	<div id="wmp_waitlist_action">
                                            <?php if ($premium_link != ''):?>
                                                <a href="<?php echo $premium_link;?>" target="_blank" class="btn orange smaller">Go Premium</a>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="name">Theme #3</div>
                        <div class="content">
                        	<span>Content type</span>
                        	<div class="wordpress-icon"></div>
                            <div class="tumblr-icon"></div>
                        	<div class="rss-icon"></div>
                            <div class="facebook-icon"></div>
                            <div class="twitter-icon"></div>
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
                        	<div class="color-" title="Headlines and primary texts">1</div>
                            <div class="color-" title="Article background">2</div>
                            <div class="color-" title="Article border">3</div>
                            <div class="color-" title="Secondary texts">4</div>
                            <div class="color-" title="Category label">5</div>
                            <div class="color-" title="Buttons">6</div>
                            <div class="color-" title="Menu">7</div>
                            <div class="color-" title="Forms">8</div>
                        </div>
                        <div class="spacer-15"></div>
                        <!-- add radio buttons -->
                        <input type="radio" name="wmp_edittheme_colorscheme" id="wmp_edittheme_colorscheme" value="1" <?php if ($color_scheme == 1) echo "checked";?> />
                        <div class="colors">
                        	<div class="color-1-1" title="Headlines and texts"></div>
                            <div class="color-1-2" title="Article background"></div>
                            <div class="color-1-3" title="Article border"></div>
                            <div class="color-1-4" title="Secondary texts"></div>
                            <div class="color-1-5" title="Category label"></div>
                            <div class="color-1-6" title="Buttons"></div>
                            <div class="color-1-7" title="Menu"></div>
                            <div class="color-1-8" title="Forms"></div>
                        </div>
                        <div class="spacer-20"></div>
                        <input type="radio" name="wmp_edittheme_colorscheme" id="wmp_edittheme_colorscheme" value="2" <?php if ($color_scheme == 2) echo "checked";?> />
                        <div class="colors">
                        	<div class="color-2-1" title="Headlines and texts"></div>
                            <div class="color-2-2" title="Article background"></div>
                            <div class="color-2-3" title="Article border"></div>
                            <div class="color-2-4" title="Secondary texts"></div>
                            <div class="color-2-5" title="Category label"></div>
                            <div class="color-2-6" title="Buttons"></div>
                            <div class="color-2-7" title="Menu"></div>
                            <div class="color-2-8" title="Forms"></div>
                        </div>
                        <div class="spacer-20"></div>
                        <input type="radio" name="wmp_edittheme_colorscheme" id="wmp_edittheme_colorscheme" value="3" <?php if ($color_scheme == 3) echo "checked";?> />
                        <div class="colors">
                        	<div class="color-3-1" title="Headlines and texts"></div>
                            <div class="color-3-2" title="Article background"></div>
                            <div class="color-3-3" title="Article border"></div>
                            <div class="color-3-4" title="Secondary texts"></div>
                            <div class="color-3-5" title="Category label"></div>
                            <div class="color-3-6" title="Buttons"></div>
                            <div class="color-3-7" title="Menu"></div>
                            <div class="color-3-8" title="Forms"></div>
                        </div>
                        <div class="spacer-30"></div>
                        <!-- start notice -->
                        <div class="notice notice-top left" style="width: 345px;">
                            <span>
                                The above color scheme will impact the following sections within the mobile web application:<br/><br/>
			
                                1.&nbsp;Headlines and primary texts<br/>
                                2.&nbsp;Article background<br/>
                                3.&nbsp;Article border<br/>
                                4.&nbsp;Secondary texts<br/>
                                5.&nbsp;Category label<br/>
                                6.&nbsp;Buttons<br/>
                                7.&nbsp;Menu<br/>
                                8.&nbsp;Forms<br/>
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
                
                <div class="notice notice-left right" style="width: 265px;">
                    <span>
                        Add your logo in a .png format with a transparent background. This will be displayed on the cover of your app.<br /><br /> 
                        Your icon should be square with a recommended size of 256 x 256 px. This will be displayed when the app will be added to the homescreen.<br /><br /> 
                        The file size for uploaded images should not exceed 1MB.
                    </span>
                </div>
                <div class="spacer-0"></div>
            </div>
            
            
            <div class="spacer-15"></div>
            
            <div class="details branding">
            	
                <h2 class="title">Customize Your App's Cover</h2>
                <div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-20"></div>
                <p>The default theme comes with 6 abstract covers that are randomly displayed on the loading screen to give your app a magazine flavor. You can further personalize your mobile web application by uploading your own cover.</p>
                <div class="spacer-20"></div>
                <div class="left">
                    <form name="wmp_editcover_form" id="wmp_editcover_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_editcover&type=upload" method="post" enctype="multipart/form-data">
                       
                        <?php
                            $cover_path = WMobilePack::wmp_get_setting('cover');
                            
                            if (!file_exists(WMP_FILES_UPLOADS_DIR.$cover_path))
                                $cover_path = '';    
                        ?>
    
                        <!-- upload cover field -->
                        <div class="wmp_editcover_uploadcover" style="display: <?php echo $cover_path == '' ? 'block' : 'none';?>;">
                        
                            <label for="wmp_editcover_cover">Upload your app cover</label>
                            
                            <div class="custom-upload">
                            
                                <input type="file" id="wmp_editcover_cover" name="wmp_editcover_cover" />
                                <div class="fake-file">
                                    <input type="text" id="fakefilecover" disabled="disabled" />
                                    <a href="#" class="btn grey smaller">Browse</a>
                                </div>
                                
                                
                                <a href="javascript:void(0)" id="wmp_editcover_cover_removenew" class="remove" style="display: none;"></a>
                            </div> 
                            
                            <!-- cancel upload cover button -->
                            <div class="wmp_editcover_changecover_cancel cancel-link" style="display: none;">
                                <a href="javascript:void(0);" class="cancel">cancel</a>
                            </div>
                            <div class="field-message error" id="error_cover_container"></div>
                        
                        </div>
                        
                        <!-- cover image -->
                        <div class="wmp_editcover_covercontainer display-logo" style="display: <?php echo $cover_path != '' ? 'block' : 'none';?>;">
                        
                            <label for="branding_cover">App cover</label>
                            <div class="img" id="wmp_editcover_currentcover" style="background:url(<?php echo WMP_FILES_UPLOADS_URL.$cover_path;?>); background-size:contain; background-repeat: no-repeat; background-position: center"></div>
                            
                            <!-- edit/delete cover links -->
                            <a href="javascript:void(0);" class="wmp_editcover_changecover btn grey smaller edit">Change</a>
                            <a href="#" class="wmp_editcover_deletecover smaller remove">remove</a>
                            
                        </div>
                                    
                        <div class="spacer-20"></div>
                        
                        
                        
                        <a href="javascript:void(0);" id="wmp_editcover_send_btn" class="btn green smaller">Save</a>
    
                    </form>    
                </div>
                
                <div class="notice notice-left right" style="width: 265px;">
                    <span>
                       Your cover will be used in portrait and landscape modes, so choose something that will look good on different screen sizes.<br /><br />
                       We recommend using a square image of minimum 500 x 500 px.<br /><br />
                       The file size for uploaded images should not exceed 1MB.
                    </span>
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
    
<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            
            window.WMPJSInterface.add("UI_previewthemesgallery","WMP_THEMES_GALLERY",{'DOMDoc':window.document, 'baseThemeUrl': '<?php echo plugins_url()."/".WMP_DOMAIN.'/themes/'.WMobilePack::$wmp_basic_theme;?>'}, window);
            window.WMPJSInterface.add("UI_customizetheme","WMP_EDIT_THEME",{'DOMDoc':window.document, 'enableCustomSelects': <?php echo intval($enable_custom_selects);?>}, window);
            window.WMPJSInterface.add("UI_editimages","WMP_EDIT_IMAGES",{'DOMDoc':window.document}, window);
            window.WMPJSInterface.add("UI_editcover","WMP_EDIT_COVER",{'DOMDoc':window.document}, window);
            
        });
    }
</script>