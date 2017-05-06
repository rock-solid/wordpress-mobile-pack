<?php
// get news & whitepaper
$news_updates = WMobilePack_Admin::news_updates();

if (!empty($news_updates)):

    if (isset($news_updates["whitepaper"]) && is_array($news_updates["whitepaper"]) && !empty($news_updates["whitepaper"])):

        // If we have a single banner, display it
        if (array_key_exists("link", $news_updates["whitepaper"])) {
            $whitepaper = $news_updates["whitepaper"];
        } else {

            // Randomly select between banners
            $random_key = array_rand($news_updates["whitepaper"]);
            $whitepaper = $news_updates["whitepaper"][$random_key];
        }

        if (array_key_exists("link", $whitepaper) && array_key_exists("image", $whitepaper)):

    ?>
            <a href="<?php echo $whitepaper["link"];?>" target="_blank"><img src="<?php echo $whitepaper["image"];?>" style="width:252px; height: auto;" /></a>
            <div class="spacer-10"></div>
    <?php
        endif;
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
                    	<p class="news-title">
                            <strong>
                                <?php if(isset($news["link"]) && $news["link"] != ''):?>
                                    <a href="<?php echo $news["link"];?>" target="_blank" title="read more"><?php echo $news["title"];?></a>
                                <?php
                                    else:
                                        echo $news["title"];
                                    endif;

                                ?>
                            </strong>
                        </p>
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
            <a href="https://www.facebook.com/wpmobilepack" target="_blank" title="Facebook" class="facebook"></a>
            <a href="https://twitter.com/wpmobilepack" target="_blank" title="Twitter" class="twitter"></a>
        </div>
    </div>

    <div class="spacer-15"></div>

    <script type="text/javascript">
    	jQuery(document).ready(function(){
    		jQuery('#wmp_news_updates').perfectScrollbar();
    	});
    </script>

<?php endif;?>
