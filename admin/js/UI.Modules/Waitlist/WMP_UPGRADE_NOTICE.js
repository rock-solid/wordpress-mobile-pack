/*****************************************************************************************************/
/*                                                                                                   */
/*                                    	'UPGRADE ADMIN NOTICE'				                         */
/*                                                                                                   */
/*****************************************************************************************************/

function WMP_UPGRADE_NOTICE(){

    var JSObject = this;
    this.changingStatus = false;

    /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                            FUNCTION INIT                                          */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.init = function(){
        this.addButtonsActions();
    };


    /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION ADD BUTTONS ACTIONS                                     */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.addButtonsActions = function(){

        var $upgradeNotice = jQuery('.wmp_upgrade_notice .notice-dismiss');

        if ($upgradeNotice.length > 0) {

            $upgradeNotice.on("click", function () {

                if (JSObject.changingStatus == true)
                    return;

                JSObject.changingStatus = true;

                jQuery.post(
                    ajaxurl,
                    {
                        'action': 'wmp_settings_save',
                        'wmp_option_upgrade_notice_updated': 0
                    },
                    function (response) {

                        JSObject.changingStatus = false;
                    }
                );
            });
        }
    }
}

// normally this part will be added in the html document using JSInterface, but this notice can appear outside the admin pages
if (window.WMPJSInterface && window.WMPJSInterface != null){
    jQuery(document).ready(function(){

        var WMP_UI_upgradenotice = new WMP_UPGRADE_NOTICE();
        WMP_UI_upgradenotice.init();
    });
}