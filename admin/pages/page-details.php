<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){

            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<?php
    // Load Free or Premium menu
    $menu_type = 'free';

    if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != '') {

        $menu_type = 'premium_classic';

        $kit_type = WMobilePack::get_kit_type();
        if ($kit_type == 'wpmp'){
            $menu_type = 'premium_wpmp';
        }
    }
?>
<div id="wmpack-admin">
    <div class="spacer-60"></div>
    <!-- set title -->
    <h1><?php echo WMP_PLUGIN_NAME.' '.WMP_VERSION;?><?php echo $menu_type != 'free' ? ' - Premium' : '';?></h1>
    <div class="spacer-20"></div>
    <div class="content">
        <div class="left-side">

            <!-- add nav menu -->
            <?php
                if ($menu_type == 'free')
                    include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php');
                elseif ($menu_type == 'premium_wpmp')
                    include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu-premium.php');
            ?>
            <div class="spacer-0"></div>

            <div class="details">
                <h2 class="title"><?php echo $page->post_title;?></h2>
                <div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <p>
                    Make sure you edit the content of your pages to look as sharp as possible in your mobile web application.<br/>
                    <strong>Editing the content from the below section will not change your desktop page.</strong>
                </p>
                <div class="spacer-15"></div>
                <form name="wmp_pageedit_form" id="wmp_pageedit_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_content_pagedetails" method="post">
                    <input type="hidden" name="wmp_pageedit_id" id="wmp_pageedit_id" value="<?php echo $page->ID;?>" />
                    <div class="message-container error" id="pageedit_message_container"></div>

                    <?php $args = array("textarea_name" => "wmp_pageedit_content");?>
                    <?php wp_editor( $content, 'wmp_pageedit_content',$args);?>

                    <div class="field-message error" id="error_content_container"></div>

                    <div class="spacer-20"></div>
                    <div class="inline-btns-container">
                        <a href="javascript:void(0);" id="wmp_pageedit_send_btn" class="btn blue smaller spaced-right" style="cursor: pointer; opacity: 1;">Save</a>
                        <a href="<?php echo add_query_arg(array('page'=>'wmp-options-content'), network_admin_url('admin.php'));?>" class="btn grey smaller" style="cursor: pointer; opacity: 1; text-transform:none;">Back</a>
                    </div>
                </form>


                <div class="spacer-0"></div>
            </div>
            <div class="spacer-15"></div>
        </div>


        <div class="right-side">
            <!-- waitlist form -->
            <?php
                if ($menu_type == 'free')
                    include_once(WMP_PLUGIN_PATH.'admin/sections/waitlist.php');
            ?>

            <!-- add feedback form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/feedback.php'); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            window.WMPJSInterface.add("UI_pagedetails","WMP_PAGE_DETAILS",{'DOMDoc':window.document}, window);
        });
    }
</script>

