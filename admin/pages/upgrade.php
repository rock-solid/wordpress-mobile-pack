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

    <div class="more">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>

            <?php
            $page_content = WMobilePack_Admin::more_updates();

            if (is_array($page_content) && !empty($page_content)):
                if (array_key_exists('premium', $page_content)):
                    ?>

                    <div class="details">
                        <div class="spacer-10"></div>

                        <?php if (array_key_exists('title', $page_content['premium'])):?>
                            <h1><?php echo $page_content['premium']['title'];?></h1>
                            <div class="spacer-30"></div>
                        <?php endif;?>

                        <?php if (array_key_exists('showcase_image',$page_content['premium'])):?>
                            <div class="showcase">
                                <img src="<?php echo $page_content['premium']['showcase_image'];?>" width="671">
                            </div>
                            <div class="spacer-30"></div>
                        <?php endif;?>

                        <?php if (array_key_exists('packages', $page_content['premium']) && is_array($page_content['premium']['packages'])): ?>

                            <?php
                            $feed_url = '';

                            if (get_bloginfo('atom_url') != null && get_bloginfo('atom_url') != '')
                                $feed_url = '&feedurl='.urlencode(get_bloginfo('atom_url'));
                            elseif (get_bloginfo('rss2_url') != null && get_bloginfo('rss2_url') != '')
                                $feed_url = '&feedurl='.urlencode(get_bloginfo('rss2_url'));
                            ?>

                            <?php foreach ($page_content['premium']['packages'] as $package):?>

                                <div class="package">

                                    <?php if (array_key_exists('title',$package)):?>
                                        <h2><?php echo $package['title'];?></h2>
                                        <div class="spacer-20"></div>
                                    <?php endif;?>

                                    <?php if (array_key_exists('description',$package)):?>
                                        <p><?php echo $package['description'];?></p>
                                        <div class="spacer-20"></div>
                                    <?php endif;?>

                                    <?php if (array_key_exists('features', $package) && is_array($package['features'])): ?>

                                        <div class="features-list">
                                            <?php foreach ($package['features'] as $package_feature):?>
                                                <div>
                                                    <?php if (array_key_exists('icon', $package_feature) && array_key_exists('text', $package_feature)):?>
                                                        <span class="icon-<?php echo $package_feature['icon'];?>"></span>
                                                        <span><?php echo $package_feature['text'];?></span>
                                                    <?php endif;?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif;?>
                                </div>
                            <?php endforeach;?>

                            <div class="spacer-20"></div>

                            <?php foreach ($page_content['premium']['packages'] as $package):?>
                                <?php if (array_key_exists('button_text', $package) && array_key_exists('button_link', $package)):?>
                                    <div class="upgrade-btns">
                                        <?php
                                        $btn_link = $package['button_link'].'&wmp_v=21';

                                        if (array_key_exists('use_feed_param', $package) && $package['use_feed_param'] == 1)
                                            $btn_link .= $feed_url;
                                        ?>
                                        <a href="<?php echo $btn_link;?>" class="btn orange smaller" target="_blank"><?php echo $package['button_text'];?></a>

                                        <?php if (array_key_exists('button_subtext', $package)):?>
                                            <div class="upgrade-subtext">
                                                <p><?php echo $package['button_subtext'];?></p>
                                            </div>
                                        <?php endif;?>
                                    </div>
                                <?php endif;?>
                            <?php endforeach;?>
                        <?php endif;?>

                        <div class="spacer-20"></div>
                        <?php if (array_key_exists('subtext', $page_content['premium'])):?>
                            <div class="try-it-subtext">
                                <div class="spacer-10"></div>
                                <?php echo $page_content['premium']['subtext'];?>
                            </div>
                        <?php endif;?>
                        <div class="spacer-20"></div>
                    </div>
                <?php
                endif;
            elseif ($page_content == 'warning'):
                ?>
                <div class="details">
                    <div class="spacer-10"></div>
                    <div class="message-container warning">
                        <div class="wrapper">
                            <div class="title">
                                <h2 class="underlined">Can't check for updates!</h2>
                            </div>
                            <span>We are unable to display the content on this page due to the fact that both <a href="https://php.net/manual/en/book.curl.php" target="_blank">cURL</a> and <a href="http://www.php.net/manual/en/function.fopen.php" target="_blank">fopen</a> are disabled.</span>
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                </div>
            <?php endif;?>
        </div>

        <div class="right-side">
            <!-- waitlist form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/waitlist.php'); ?>

            <!-- add feedback form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/feedback.php'); ?>
        </div>
    </div>
</div>