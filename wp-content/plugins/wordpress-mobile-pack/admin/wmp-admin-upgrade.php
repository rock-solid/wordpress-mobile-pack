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

    <?php $page_content = WMobilePackAdmin::wmp_more_updates();?>
    <div class="more">
        <div class="left-side"> 
        
            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>
    		
            <?php if(is_array($page_content) && !empty($page_content)):?>
				<?php if (array_key_exists('premium', $page_content)):?>
                    <div class="details go-premium-white">
                        <div class="spacer-10"></div>
                        <?php if (array_key_exists('title', $page_content['premium'])):?>
                            <h1><?php echo $page_content['premium']['title'];?></h1>
                            <div class="spacer-20"></div>
                        <?php endif;?>
                       <?php
							$feed_url = '';
							
							if (get_bloginfo('atom_url') != null && get_bloginfo('atom_url') != '')
								$feed_url = '&feedurl='.urlencode(get_bloginfo('atom_url'));
							elseif (get_bloginfo('rss2_url') != null && get_bloginfo('rss2_url') != '')
								$feed_url = '&feedurl='.urlencode(get_bloginfo('rss2_url'));	
						?>
					   
					   <?php if (array_key_exists('showcase_image',$page_content['premium'])):?>
                             <div class="showcase">
                                <a href="<?php echo $page_content['premium']['button_link'].$feed_url.'&wmp_v=21';?>" target="_blank"><img src="<?php echo $page_content['premium']['showcase_image'];?>" width="671"></a>
                            </div>
                        <?php endif;?>
                        <div class="spacer-20"></div>
                        <div class="spacer-20"></div>
                        
                        <div class="premium-bg">
                            <?php if (array_key_exists('list', $page_content['premium'])): ?>
                            	<div class="features">
                                	<?php foreach ($page_content['premium']['list'] as $key => $feature_item):?>
                                    	 <div class="feature">
											<?php if (array_key_exists('image',$feature_item)):?>
                                                <img src="<?php echo $feature_item['image'];?>" />
                                                <div class="spacer-10"></div>
                                            <?php endif;?>
                                            
                                            <?php if (array_key_exists('title',$feature_item)):?>
                                                <p class="title"><?php echo $feature_item['title'];?></p>
                                                <div class="spacer-5"></div>
                                            <?php endif;?>
                                            <?php if (array_key_exists('text',$feature_item)):?>
                                                <p><?php echo $feature_item['text'];?></p>
                                            <?php endif;?>
                                            
                                        </div>
                                        
										<?php if(($key + 1) % 3 == 0) :?>                                        	
                                            </div>
                                            <div class="spacer-20"></div>
                                            <div class="features">
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </div>
                            <?php endif;?>
                            
                        <div class="spacer-20"></div>
                        <?php if (array_key_exists('button_text', $page_content['premium']) && array_key_exists('button_link', $page_content['premium'])):?>
                            <div class="try-it">
                                <a href="<?php echo $page_content['premium']['button_link'].$feed_url.'&wmp_v=21';?>" class="btn orange smaller" target="_blank"><?php echo $page_content['premium']['button_text'];?></a>
                            </div>                        
                        <?php endif;?>
                    </div>
                <?php endif;?>
            <?php endif;?>
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