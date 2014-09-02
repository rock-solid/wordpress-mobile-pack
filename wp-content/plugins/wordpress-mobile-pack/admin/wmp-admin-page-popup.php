
<div class="overlay-bg" style="width:600px;background-color:#f00;" id="pageedit"> 
    <div class="overlay-content contentpage"> 
    	<div class="relative close-btn"><a class="close-x" id="close-btn" value="Close" ></a></div>
        <div class="header">
            <h3><?php echo $page->post_title;?></h3>
        </div>
        <div class="inner">  
            <form name="wmp_pageedit_form" id="wmp_pageedit_form" action="<?php echo admin_url('admin-ajax.php'); ?>?action=wmp_pagedetails_save" method="post">
            	<input type="hidden" name="wmp_pageedit_id" id="wmp_pageedit_id" value="<?php echo $page->ID;?>" />
                <div class="message-container error" id="pageedit_message_container"></div>
                
                
                <label for="wmp_pageedit_content">Content* <span class="info" title="Please specify the title of your news category (max. 30 characters). Only letters, spaces, hyphens(-) and dots(.) are allowed."></span></label>
				
				<?php if(1):?>
					<?php $args = array("textarea_name" => "wmppageeditcontent",'quicktags' => true,'tinymce' => true);?>
					<?php wp_editor( $content, 'wmppageeditcontent',$args);?>
					
				<?php else:?>
                	<textarea id="wmp_pageedit_content" name="wmp_pageedit_content" cols="60" rows="20"><?php echo $content;?></textarea>
                
				<?php endif;?>
                
                
                <div class="field-message error" id="error_content_container"></div> 
                                        
                <div class="spacer-20"></div>
                <a href="javascript:void(0);" id="wmp_pageedit_send_btn" class="btn blue smaller" style="cursor: pointer; opacity: 1;">Save</a>
                    
            </form>
    	</div>
    </div>

    <script type="text/javascript">
        if (window.WMPJSInterface && window.WMPJSInterface != null){
        	jQuery(document).ready(function(){
                window.WMPJSInterface.add("UI_pagepopup","WMP_PAGE_POPUP",{'DOMDoc':window.document}, window);
				window.WMPJSInterface.UI_pagepopup.init();
			});
		}
    </script>
</div>
    
