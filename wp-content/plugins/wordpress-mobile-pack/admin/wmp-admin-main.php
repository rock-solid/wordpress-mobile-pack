<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            
            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<div id="wmpack-admin">
    <div class="spacer-20"></div>
    <!-- set title -->
    <h1><?php echo WMP_PLUGIN_NAME;?></h1>
    <div class="spacer-20"></div>
    
    <?php $page_content = WMobilePackAdmin::wmp_whatsnew_updates();?>
    <div class="whats-new">
        <div class="left-side"> 
        
            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>
            
            <?php if (array_key_exists('header', $page_content)):?>
                <div class="details">
                    <div class="spacer-10"></div>
                    
                    <?php if (array_key_exists('text', $page_content['header'])):?>
                        <?php echo $page_content['header']['text'];?>
                        <div class="spacer-20"></div>
                    <?php endif;?>
                    
                    <?php if (array_key_exists('banner', $page_content['header'])):?>
                        <div class="showcase">
                        	<img src="<?php echo $page_content['header']['banner'];?>" />
                        </div>
                        <div class="spacer-20"></div>
                    <?php endif;?>
                    
                    <div class="spacer-20"></div>
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
                    
                    <div class="spacer-20"></div>
                    
                    <?php if (array_key_exists('list', $page_content['features']) && is_array($page_content['features']['list'])):?>
                        
                        <?php 
                            $pos = 'left';
                            
                            foreach ($page_content['features']['list'] as $feature):
                                
                        
                        ?>
                        
                            <div class="feature <?php echo $pos;?>">
                                <?php if (array_key_exists('image', $feature)):?>
                            	   <img src="<?php echo $feature['image'];?>" />
                                <?php endif;?>
                                
                                <div class="text">
                                    <?php if (array_key_exists('title', $feature)):?>
                                	   <span class="title"><?php echo $feature['title'];?></span>
                                    <?php endif;?>
                                    
                                    <?php if (array_key_exists('text', $feature)):?>
                                	   <span><?php echo $feature['text'];?></span>
                                    <?php endif;?>
                                    
                                </div>
                            </div>
                            <div class="spacer-10"></div>
                            
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
        </div>
        <div class="right-side"> 
            <!-- add news and updates -->
            <?php include_once('sections/wmp-news.php'); ?>
            <div class="spacer-15"></div>

            <!-- add feedback form -->
            <?php include_once('sections/wmp-feedback.php'); ?>
        </div>
    </div>
</div>