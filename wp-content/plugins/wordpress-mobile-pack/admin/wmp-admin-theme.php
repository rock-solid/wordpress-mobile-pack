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
    <h1><?php echo WMP_PLUGIN_NAME;?></h1>
	<div class="spacer-20"></div>
	<div class="look-and-feel">
        <div class="left-side">
        
            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>
            
            <!-- add content form -->
            <div class="details">
                <div class="spacer-10"></div>
                <p>Lorem ipsum dolor sit amet, nec accusamus assentior in, per ea probo percipit ullamcorper. An mel animal menandri vituperata. Vis an solet ocurreret, sit laudem semper perfecto ex, vix an nibh tacimates. Ne usu duis ignota oblique.</p>
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
                        <div class="image" style="background:url(<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/theme-1.jpg);">
                        	<div class="relative">
                            	<div class="overlay">
                                	<div class="spacer-70"></div>
                            		<div class="preview"></div>
                                    <div class="spacer-10"></div>
                                    <div class="text">Preview theme</div>
                            	</div>
                            </div>
                        </div>
                        <div class="name">Blogish</div>
                        <div class="content">Content type
                        	<div class="wordpress-icon"></div>
                        </div>
                    </div>
                    <div class="theme waitlist">
                    	<div class="corner relative inactive">
                            <div class="indicator"></div>
                        </div>
                        <div class="image" style="background:url(<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/theme-2.jpg);">
                        	<div class="relative">
                            	<div class="overlay">
                                	<div class="spacer-30"></div>
                            		<div class="preview"></div>
                                    <div class="spacer-10"></div>
                                    <div class="text">Preview theme</div>
                                    <div class="spacer-5"></div>
                                    <!--<a href="#" class="btn blue smaller">Join Waitlist</a>
                                    <div class="text">
                                    	And get notified when<br> available	
                                    </div>-->
                                    <div class="info">
                                    	<input type="text" placeholder="your email" class="smaller" /> <a href="#" class="btn blue smallest">Ok</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="name">Business</div>
                        <div class="content">Content type
                        	<div class="facebook-icon"></div>
                            <div class="twitter-icon"></div>
                            <div class="rss-icon"></div>
                        </div>
                    </div>
                    <div class="theme waitlist added">
                    	<div class="corner relative inactive">
                            <div class="indicator"></div>
                        </div>
                        <div class="image" style="background:url(<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/theme-3.jpg);">
                        	<div class="relative">
                            	<div class="overlay">
                                	<div class="spacer-70"></div>
                                    <div class="text">
										<span>ADDED TO<br>WAITLIST</span>                                    	
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="name">Lifestyle</div>
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
                <div class="spacer-10"></div>
                <p>Customize your mobile web app theme by choosing different color schemes and fonts from the section below.</p>
                <div class="spacer-20"></div>
                <div class="spacer-20"></div>
                
                <form name="edittheme_form" id="edittheme_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_settings_save" method="post" enctype="multipart/form-data">
                
                    <div class="color-schemes">
                        <p class="section-header">Color scheme</p>
                        <div class="spacer-20"></div>
                        <?php 
                            $color_scheme = WMobilePack::wmp_get_setting('color_scheme');
                            if ($color_scheme == '')
                                $color_scheme = 1;
                        ?>
                        <!-- add radio buttons -->
                        <input type="radio" name="edittheme_colorscheme" id="edittheme_colorscheme" value="1" <?php if ($color_scheme == 1) echo "checked";?> />
                        <div class="colors">
                        	<div class="color-1-1"></div>
                            <div class="color-1-2"></div>
                            <div class="color-1-3"></div>
                            <div class="color-1-4"></div>
                            <div class="color-1-5"></div>
                            <div class="color-1-6"></div>
                            <div class="color-1-7"></div>
                            <div class="color-1-8"></div>
                        </div>
                        <div class="spacer-20"></div>
                        <input type="radio" name="edittheme_colorscheme" id="edittheme_colorscheme" value="2" <?php if ($color_scheme == 2) echo "checked";?> />
                        <div class="colors">
                        	<div class="color-2-1"></div>
                            <div class="color-2-2"></div>
                            <div class="color-2-3"></div>
                            <div class="color-2-4"></div>
                            <div class="color-2-5"></div>
                            <div class="color-2-6"></div>
                            <div class="color-2-7"></div>
                            <div class="color-2-8"></div>
                        </div>
                        <div class="spacer-20"></div>
                        <input type="radio" name="edittheme_colorscheme" id="edittheme_colorscheme" value="3" <?php if ($color_scheme == 3) echo "checked";?> />
                        <div class="colors">
                        	<div class="color-3-1"></div>
                            <div class="color-3-2"></div>
                            <div class="color-3-3"></div>
                            <div class="color-3-4"></div>
                            <div class="color-3-5"></div>
                            <div class="color-3-6"></div>
                            <div class="color-3-7"></div>
                            <div class="color-3-8"></div>
                        </div>
                        <div class="spacer-20"></div>
                    </div>
                    
                    <div class="font-chooser">
                        <p class="section-header">Fonts</p>
                        <div class="spacer-20"></div>
                        
                        <!-- add radio buttons -->
                        <?php 
                            $font_headlines = WMobilePack::wmp_get_setting('font_headlines');
                            if ($font_headlines == '')
                                $font_headlines = 'Roboto Condensed';
                        ?>
                        
                        <label for="edittheme_fontheadlines">Headlines</label>
                        <select name="edittheme_fontheadlines" id="edittheme_fontheadlines">
                        	<option value="Roboto Condensed" <?php if ($font_headlines == "Roboto Condensed") echo "selected";?>>Roboto Condensed</option>
                            <option value="Georgia" <?php if ($font_headlines == "Georgia") echo "selected";?>>Georgia</option>
                            <option value="Times New Roman" <?php if ($font_headlines == "Times New Roman") echo "selected";?>>Times New Roman</option>
                            <option value="Open Sans" <?php if ($font_headlines == "Open Sans") echo "selected";?>>Open Sans</option>
                        </select>
                        <div class="spacer-10"></div>
                        
                        <?php 
                            $font_subtitles = WMobilePack::wmp_get_setting('font_subtitles');
                            if ($font_subtitles == '')
                                $font_subtitles = 'Roboto Condensed';
                        ?>
                        
                        <label for="edittheme_fontsubtitles">Subtitles</label>
                        <select name="edittheme_fontsubtitles" id="edittheme_fontsubtitles">
                        	<option value="Roboto Condensed" <?php if ($font_subtitles == "Roboto Condensed") echo "selected";?>>Roboto Condensed</option>
                            <option value="Georgia" <?php if ($font_subtitles == "Georgia") echo "selected";?>>Georgia</option>
                            <option value="Times New Roman" <?php if ($font_subtitles == "Times New Roman") echo "selected";?>>Times New Roman</option>
                            <option value="Open Sans" <?php if ($font_subtitles == "Open Sans") echo "selected";?>>Open Sans</option>
                        </select>
                        <div class="spacer-10"></div>
                        
                        <?php 
                            $font_paragraphs = WMobilePack::wmp_get_setting('font_paragraphs');
                            if ($font_paragraphs == '')
                                $font_paragraphs = 'Roboto Condensed';
                        ?>
                        
                        <label for="edittheme_fontparagraphs">Paragraphs</label>
                        <select name="edittheme_fontparagraphs" id="edittheme_fontparagraphs">
                        	<option value="Roboto Condensed" <?php if ($font_paragraphs == "Roboto Condensed") echo "selected";?>>Roboto Condensed</option>
                            <option value="Georgia" <?php if ($font_paragraphs == "Georgia") echo "selected";?>>Georgia</option>
                            <option value="Times New Roman" <?php if ($font_paragraphs == "Times New Roman") echo "selected";?>>Times New Roman</option>
                            <option value="Open Sans" <?php if ($font_paragraphs == "Open Sans") echo "selected";?>>Open Sans</option>
                        </select>
                        <div class="spacer-20"></div>        
                    </div>
                </form>
                <div class="spacer-20"></div>
                <a href="javascript:void(0);" id="edittheme_send_btn" class="btn green smaller" >Save</a> 
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
            
            window.JSInterface.add("UI_customizetheme","EDIT_THEME",{'DOMDoc':window.document}, window);
        });
    }
</script>