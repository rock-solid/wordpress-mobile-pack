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

    <div class="monetize">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>

            <?php
                $page_content = WMobilePackAdmin::wmp_more_updates();

                if (is_array($page_content) && !empty($page_content)):
                    if (array_key_exists('monetize', $page_content)):
            ?>

                    <div class="details monetize">
                        <div class="spacer-10"></div>

                        <?php if (array_key_exists('title', $page_content['monetize'])):?>
                            <h2 class="title"><?php echo $page_content['monetize']['title'];?></h2>
                            <div class="spacer-15"></div>
                            <div class="grey-line"></div>
                            <div class="spacer-15"></div>
                        <?php endif;?>

                        <?php if (array_key_exists('description', $page_content['monetize'])):?>
                            <?php echo $page_content['monetize']['description'];?>
                            <div class="spacer-20"></div>
                        <?php endif;?>

                        <div class="video-container">
                            <?php if (array_key_exists('video', $page_content['monetize'])):?>
                                <?php echo $page_content['monetize']['video'];?>
                                <div class="spacer-20"></div>
                            <?php endif;?>

                            <a href="<?php echo add_query_arg(array('page'=>'wmp-options-upgrade'), network_admin_url('admin.php'));?>" class="btn orange smaller">Available in PRO</a>
                        </div>
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
            <!-- add waitlist form -->
            <?php include_once('sections/wmp-waitlist.php'); ?>
            <!-- add feedback form -->
            <?php include_once('sections/wmp-feedback.php'); ?>
        </div>
    </div>
</div>