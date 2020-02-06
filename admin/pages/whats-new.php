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
    <h1><?php echo WMP_PLUGIN_NAME.' '.WMP_VERSION;?></h1>
    <div class="spacer-20"></div>
    <?php $page_content = WMobilePack_Admin::whatsnew_updates();?>
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

        	<?php elseif ($page_content == 'warning'):?>
            	<div class="details">
                    <div class="spacer-10"></div>
                    <div class="message-container warning">
                        <div class="wrapper">
                            <div class="title">
                                <h2 class="underlined">Can't check for updates!</h2>
                            </div>
                            <span>We are unable to display the updates on this page due to the fact that both <a href="https://php.net/manual/en/book.curl.php" target="_blank">cURL</a> and <a href="http://www.php.net/manual/en/function.fopen.php" target="_blank">fopen</a> are disabled.</span>
                        </div>
                    </div>
                </div>

                <div class="details features">
                    <h2 class="title">Get Started with WordPress Mobile Pack</h2>
                    <div class="spacer-15"></div>
                    <div class="grey-line"></div>
                    <div class="spacer-15"></div>

                    <div class="feature left">

                        <a href="https://www.youtube.com/watch?v=elxjfdbAoqM&feature=youtu.be" target="_blank">
                            <img src="https://rcksld-wpmp.s3.amazonaws.com/dashboard/quick_start/images/customize.png"
                                 title="Step 1. Customize your mobile web app's look &amp; feel"/>
                        </a>

                        <div class="text">
                            <span class="title">Step 1. Customize your mobile web app's look &amp; feel</span>

                            <p>Customize your mobile web application by choosing from the available color schemes &amp;
                                fonts, adding your logo and app icon. The default theme comes with 6 abstract covers
                                that are randomly displayed on the loading screen to give your app a magazine flavor.
                                You can further personalize your mobile web application by uploading your own
                                cover.<br/><br/>Check out this <a
                                    href="https://www.youtube.com/watch?v=elxjfdbAoqM&feature=youtu.be" target="blank">short
                                    video tutorial</a> to see how it's done.
                            </p>
                        </div>
                    </div>
                    <div class="spacer-0"></div>

                    <div class="feature right">

                        <img src="https://rcksld-wpmp.s3.amazonaws.com/dashboard/quick_start/images/responsive.png"
                             title="Step 2. Decide on the content you want for your app"/>

                        <div class="text">
                            <span class="title">Step 2. Decide on the content you want for your app</span>

                            <p>From the <strong>Content</strong> tab, choose what categories & pages to be displayed in
                                your mobile web application. Click on the rows to show/hide categories & pages and order
                                them by dragging the corresponding row on the desired position.</p>
                        </div>
                    </div>
                    <div class="spacer-0"></div>

                    <div class="feature left">

                        <img src="https://rcksld-wpmp.s3.amazonaws.com/dashboard/quick_start/images/social-buttons.png"
                             title="Step 3. Edit your app's settings"/>

                        <div class="text">
                            <span class="title">Step 3. Edit your app's settings</span>

                            <p>From the <strong>Settings</strong> tab, edit the <em>Display Mode</em> of your app to
                                enable/disable it for your mobile readers. The <em>Preview</em> mode lets you edit your
                                app without it being visible to anyone else.<br/><br/> You can also add your own <em>Google
                                    Analytics ID</em> to get more insights on the way your mobile visitors interact with
                                the application.</p>
                        </div>
                    </div>
                    <div class="spacer-0"></div>

                    <div class="feature right">

                        <img src="https://rcksld-wpmp.s3.amazonaws.com/dashboard/quick_start/images/analytics.png"
                             title="Step 4. Grow your mobile traffic"/>

                        <div class="text">
                            <span class="title">Step 4. Grow your mobile traffic</span>

                            <p>Join our free <strong>Mobile Growth Academy</strong> and receive weekly lessons on how to
                                improve your mobile strategy and grow your mobile traffic. Upon graduating the first 10
                                mobile growth lessons, you'll be awarded with a <strong>30% discount
                                certificate</strong> to be used with <a
                                href="<?php echo isset($wpmp_upgrade_pro_link) ? $wpmp_upgrade_pro_link : WMobilePack_Admin::upgrade_pro_link();?>" target="blank">WP
                                    Mobile Pack PRO</a>.
                        </div>
                    </div>
                    <div class="spacer-0"></div>

                </div>

            <?php endif;?>

            <!-- add subscribe to mobile growth -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/subscribe.php'); ?>

        </div>
        <div class="right-side">
            <!-- add news and updates -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/news.php'); ?>

            <!-- add feedback form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/feedback.php'); ?>
        </div>
    </div>
</div>
