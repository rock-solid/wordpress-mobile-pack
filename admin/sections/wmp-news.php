<?php 
// get news & whitepaper
$news_updates = WMobilePackAdmin::wmp_news_updates();

if (!empty($news_updates)):

    if (isset($news_updates["whitepaper"]) && is_array($news_updates["whitepaper"]) && !empty($news_updates["whitepaper"])):
    ?>
        <a href="<?php echo $news_updates["whitepaper"]["link"];?>" target="_blank"><img src="<?php echo $news_updates["whitepaper"]["image"];?>" /></a>
        <div class="spacer-10"></div>
    <?php 
    endif;
    
    $arrNews = $news_updates['news'];
    
    if (!empty($arrNews) && is_array($arrNews )):
    	// if the news array is empty this section will not be displayed
    ?>
        <div class="updates">
            <h2>News &amp; Resources</h2> 
            <div class="spacer-20"></div>
            <div class="details" id="wmp_news_updates">
                <!-- start news and updates -->
                <?php foreach($arrNews as $key => $news):?>
                    <?php if(isset($news["title"]) && $news["title"] != ''):?>
                    	<p><strong><?php echo $news["title"];?></strong></p>
                        <div class="spacer-2"></div>
                    <?php endif;?>
                    <p>
    					<?php echo $news["content"];?> 
                        <?php if(isset($news["link"]) && $news["link"] != ''):?>
                        	<a href="<?php echo $news["link"];?>" target="_blank" title="read more">read more</a>
                        <?php endif;?> 
                    </p> 
                    <div class="spacer-20"></div>
                    <div class="grey-dotted-line"></div>
                    <?php if($key < (count($arrNews) - 1)):?>
                    	<div class="spacer-20"></div>
                    <?php endif;?>
                <?php endforeach;?>    
            </div>
        </div>
        <div class="spacer-5"></div>
    <?php endif;?>
    
    <!-- add appticles social -->
    <div class="appticles-updates">
        <!-- add content -->
        <div class="social">
            <a href="https://www.facebook.com/appticles" target="_blank" title="Facebook" class="facebook"></a>
            <a href="https://twitter.com/appticles" target="_blank" title="Twitter" class="twitter"></a>
            <a href="https://plus.google.com/+AppticlesCom/" target="_blank" title="Google +" class="google-plus"></a>
        </div>
    </div>
    
    <div class="spacer-15"></div>
    
    <script type="text/javascript">
    	jQuery(document).ready(function(){
    		jQuery('#wmp_news_updates').perfectScrollbar();
    	});
    </script>
    
<?php endif;?>