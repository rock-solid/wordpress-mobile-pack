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
                    
					<?php if (array_key_exists('title', $page_content['header'])):?>
                        <h1><?php echo $page_content['header']['title'];?></h1>
                    <?php endif;?>
                      
				    <?php if (array_key_exists('subtitle', $page_content['header'])):?>
                     	<div class="spacer-10"></div>
                        <h1><?php echo $page_content['header']['subtitle'];?></h1>
                    <?php endif;?>
                    
                    <div class="spacer-20"></div>
                        
                    <?php 
                        $new_version = WMobilePack::wmp_new_plugin_version();
                        
                        if ($new_version !== null):
                    ?>
                        <p class="upgrade-message"><a href="<?php echo admin_url( 'plugins.php' );?>"><u>WP Mobile Pack <?php echo $new_version;?> is available. Please update now.</u></a></p>
                        <div class="spacer-20"></div>
                        
                    <?php endif;?>
                
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
                    
                    <div class="spacer-20"></div>
                    
                    <?php if (array_key_exists('list', $page_content['features']) && is_array($page_content['features']['list'])):?>
                        
                        <?php 
                            $pos = 'left';
                            
                            foreach ($page_content['features']['list'] as $feature):
                        ?>
                        
                            <div class="feature <?php echo $pos;?>">
                                <?php if (array_key_exists('image', $feature)):?>
                            	   <img src="<?php echo $feature['image'];?>" title="<?php echo array_key_exists('title', $feature) ? $feature['title'] : '';?>" />
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

            <!-- add feedback form -->
            <?php include_once('sections/wmp-feedback.php'); ?>
        </div>
    </div>
</div>