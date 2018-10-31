
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
    <h1>Publisher's Toolbox PWA <?php echo WMP_VERSION;?></h1>
    <div class="spacer-20"></div>
   <?php #$page_content = WMobilePack_Admin::whatsnew_updates();?>
    <div class="whats-new">
        <div class="left-side"> 
        
            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>
            
            <?php if(is_array($page_content) && !empty($page_content)):?>
            
				<?php if (array_key_exists('header', $page_content)):?>
                    <div class="details">
                        <div class="spacer-10"></div>
                        
                        <?php if (array_key_exists('title', $page_content['header'])):?>
                            <h1><?php echo $page_content['header']['title'];?></h1>
                        <?php endif;?>
                          
                        <?php if (array_key_exists('subtitle', $page_content['header'])):?>
                            <div class="spacer-10"></div>
                            <h1><?php echo $page_content['header']['subtitle'];?></h1>
                        <?php endif;?>
                        
                        <div class="spacer-20"></div>
                    
                        <?php if (array_key_exists('banner', $page_content['header'])):?>
                            <div class="showcase">
                                <img src="<?php echo $page_content['header']['banner'];?>" />
                            </div>
                            <div class="spacer-20"></div>
                        <?php endif;?>
                        
                        <?php if (array_key_exists('devices', $page_content['header'])):?>
                            <?php echo $page_content['header']['devices'];?>
                            <div class="spacer-20"></div>
                        <?php endif;?>
                        
                    </div>
                    <div class="spacer-10"></div>
                <?php endif;?>
                
                <?php if (array_key_exists('features', $page_content)):?>
                
                    <div class="details features">
                    
                        <?php if (array_key_exists('title', $page_content['features'])):?>
    
                            <h2 class="title"><?php echo $page_content['features']['title'];?></h2>
                            <div class="spacer-15"></div>
                            <div class="grey-line"></div>
                            <div class="spacer-15"></div>
                        <?php endif;?>

                        <?php if (array_key_exists('list', $page_content['features']) && is_array($page_content['features']['list'])):?>
                            
                            <?php 
                                $pos = 'left';
                                
                                foreach ($page_content['features']['list'] as $feature):
                            ?>
                            
                                <div class="feature <?php echo $pos;?>">

                                    <?php if (array_key_exists('image', $feature)):?>

                                        <?php if (array_key_exists('image_link', $feature)):?>
                                            <a href="<?php echo $feature['image_link'];?>" target="_blank">
                                        <?php endif;?>

                                        <img src="<?php echo $feature['image'];?>" title="<?php echo array_key_exists('title', $feature) ? $feature['title'] : '';?>" />

                                        <?php if (array_key_exists('image_link', $feature)):?>
                                            </a>
                                        <?php endif;?>

                                    <?php endif;?>

                                    <div class="text">
                                        <?php if (array_key_exists('title', $feature)):?>
                                           <span class="title"><?php echo $feature['title'];?></span>
                                        <?php endif;?>
                                        
                                        <?php if (array_key_exists('text', $feature)):?>
                                           <?php echo $feature['text'];?>
                                        <?php endif;?>
                                        
                                    </div>
                                </div>
                                <div class="spacer-0"></div>
                                
                            <?php
                                    if ($pos == 'left')
                                        $pos = 'right';
                                    else
                                        $pos = 'left';
                                        
                                endforeach;
                            ?>   
                            
                        <?php endif;?>
                    </div>
                <?php endif;?>
                
        	<?php elseif ($page_content == 'warning'): ?>
                  <?php endif;?>
            	<!--<div class="details">
                    <div class="spacer-10"></div>
                    <div class="message-container warning">
                        <div class="wrapper">
                            <div class="title">
                                <h2 class="underlined">Can't check for updates!</h2>
                            </div>
                            <span>We are unable to display the updates on this page due to the fact that both <a href="https://php.net/manual/en/book.curl.php" target="_blank">cURL</a> and <a href="http://www.php.net/manual/en/function.fopen.php" target="_blank">fopen</a> are disabled.</span> 
                        </div>
                    </div>
                </div>-->

                <div class="details features">
                    <h2 class="title">Get started with the Publisher's Toolbox PWA</h2>
                    <div class="spacer-15"></div>
                    <div class="grey-line"></div>
                    <div class="spacer-15"></div>

                    <div class="feature left">
                            <?php

                            $step1 = get_home_url()."\wp-content\plugins\wordpress-pwa\admin\images\step1.png";

                            ?>

                            <img src="<?php echo $step1; ?>"
                                 title="Step 1: Choose between two available app themes"/>
                        </a>

                        <div class="text">
                            <span class="title">Step 1: Choose between two available app themes</span>

                            <p>There are two available themes listed to choose from: Newspaper and Magazine. This will determine the layout and presentation of the content. Any one theme will be activated, simply by clicking on the preferred option. To switch between the themes, simply click to activate the alternative theme.
                            </p>
                        </div>
                    </div>
                    <div class="spacer-0"></div>

                    <div class="feature left">
                        <?php 

                        $step2 = get_home_url()."\wp-content\plugins\wordpress-pwa\admin\images\step2.png";

                        ?>
                        <img src="<?php echo $step2; ?>"
                             title="Step 2: Tailor the look & feel to suit your website"/>

                        <div class="text">
                            <span class="title">Step 2: Tailor the look & feel to suit your website</span>

                            <p>Tailor the look & feel by choosing from the available color schemes, various images, adding your logo and app icon.</p>
                        </div>
                    </div>
                    <div class="spacer-0"></div>

                    <div class="feature left">
                        <?php

                        $step3 = get_home_url()."\wp-content\plugins\wordpress-pwa\admin\images\step3.png";
                        
                        ?>
                        <img src="<?php echo $step3; ?>"
                             title="Step 3. Decide on the content to be included in your layout"/>

                        <div class="text">
                            <span class="title">Step 3: Decide on the content to be included in your layout</span>

                            <p>Initially all content will be activated to show on your mobile web application. Decide between various pages & categories to be activated/deactivated, by simply clicking on the row & selecting to activate/deactivate it. Categories & pages can also be rearranged by dragging the corresponding row to the desired position.</p>
                        </div>
                    </div>
                    <div class="spacer-0"></div>
                    <?php 

                     $step4 = get_home_url()."\wp-content\plugins\wordpress-pwa\admin\images\step4.png";   

                    ?>
                    <div class="feature left">
                        <img src="<?php echo $step4 ?>"
                             title="Step 4:  Change your settings, implement Google Analytics & social sharing"/>

                        <div class="text">
                            <span class="title">Step 4: Change your settings, implement Google Analytics & social sharing</span>

                            <p>Edit the app settings & add Google Tag Manager ID & Google Analytics Tracking Code to get insights from your visitors’ behaviour on your mobile web application. You also have the option to enable social sharing by activating/deactivating it.</p>

                        </div>
                    </div>
                    <div class="spacer-0"></div>

                </div>

        
        </div>
        <div class="right-side"> 
            <!-- add news and updates -->
            <?php #include_once(WMP_PLUGIN_PATH.'admin/sections/news.php'); ?>

            <!-- add feedback form -->
            <?php #include_once(WMP_PLUGIN_PATH.'admin/sections/feedback.php'); ?>
        </div>
    </div>
</div>