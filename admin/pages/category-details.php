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
    <div class="content">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php');?>
            <div class="spacer-0"></div>

            <div class="details branding-category">
                <h2 class="title">Add image for category "<?php echo $category->name;?>"</h2>
                <div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <p>
                    The image will be displayed in the mobile web app (categories menu).<br/>
                    <strong>Editing the content from the below section will not change your desktop category settings.</strong>
                </p>
                <div class="spacer-15"></div>
                <div class="left">
                    <form name="wmp_categoryedit_form" id="wmp_categoryedit_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_theme_editimages&type=upload" method="post" enctype="multipart/form-data">

                        <?php
                        $icon_path = '';
                        $categories_details = WMobilePack_Options::get_setting('categories_details');

                        if (is_array($categories_details)) {

                            if (array_key_exists($category->cat_ID, $categories_details)) {

                                if (is_array($categories_details[$category->cat_ID])) {

                                    if (array_key_exists('icon', $categories_details[$category->cat_ID])) {

                                        $icon_path = $categories_details[$category->cat_ID]['icon'];

                                        if ($icon_path != ''){
                                            if (!file_exists(WMP_FILES_UPLOADS_DIR . $icon_path))
                                                $icon_path = '';
                                            else
                                                $icon_path = WMP_FILES_UPLOADS_URL . $icon_path;
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                        <input type="hidden" name="wmp_categoryedit_id" id="wmp_categoryedit_id" value="<?php echo $category->cat_ID;?>" />

                        <!-- upload icon field -->
                        <div class="wmp_categoryedit_uploadicon" style="display: <?php echo $icon_path == '' ? 'block' : 'none';?>;">

                            <label for="wmp_categoryedit_icon">Upload your category image</label>

                            <div class="custom-upload">

                                <input type="file" id="wmp_categoryedit_icon" name="wmp_categoryedit_icon" />
                                <div class="fake-file">
                                    <input type="text" id="fakefileicon" disabled="disabled" />
                                    <a href="#" class="btn grey smaller">Browse</a>
                                </div>

                                <a href="javascript:void(0)" id="wmp_categoryedit_icon_removenew" class="remove" style="display: none;"></a>
                            </div>
                            <!-- cancel upload icon button -->
                            <div class="wmp_categoryedit_changeicon_cancel cancel-link" style="display: none;">
                                <a href="javascript:void(0);" class="cancel">cancel</a>
                            </div>
                            <div class="field-message error" id="error_icon_container"></div>

                        </div>

                        <!-- icon image -->
                        <div class="wmp_categoryedit_iconcontainer display-icon" style="display: <?php echo $icon_path != '' ? 'block' : 'none';?>;;">

                            <label for="branding_icon">Category image</label>
                            <img src="<?php echo $icon_path;?>" id="wmp_categoryedit_currenticon" />

                            <!-- edit/delete icon links -->
                            <a href="javascript:void(0);" class="wmp_categoryedit_changeicon btn grey smaller edit">Change</a>
                            <a href="#" class="wmp_categoryedit_deleteicon smaller remove">remove</a>
                        </div>

                        <div class="spacer-20"></div>
                        <div class="inline-btns-container">
                            <a href="javascript:void(0);" id="wmp_categoryedit_send_btn" class="btn blue smaller spaced-right" style="cursor: pointer; opacity: 1;">Save</a>
                            <a href="<?php echo add_query_arg(array('page'=>'wmp-options-content'), network_admin_url('admin.php'));?>" class="btn grey smaller" style="cursor: pointer; opacity: 1; text-transform:none;">Back</a>
                        </div>
                    </form>
                </div>


                <div class="spacer-0"></div>
            </div>
            <div class="spacer-15"></div>
        </div>


        <div class="right-side">
            <!-- waitlist form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/waitlist.php');?>

            <!-- add feedback form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/feedback.php'); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            window.WMPJSInterface.add("UI_categorydetails","WMP_CATEGORY_DETAILS",{'DOMDoc':window.document}, window);
        });
    }
</script>

