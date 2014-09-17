/*****************************************************************************************************/
/*                                                                                                   */
/*                                    	'DISCONNECT FROM APPTICLES'                                  */
/*                                                                                                   */
/*****************************************************************************************************/

function WMP_DISCONNECT(){

    var JSObject = this;

    this.type = "wmp_disconnect";

    this.DOMDoc;

    this.send_btn;
	this.redirectTo;
	this.submitUrl;
	/*****************************************************************************************************/
    /*                                                                                                   */
    /*                              FUNCTION INIT - called from WMPJSInterface                           */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.init = function(){

        // save a reference to WMPJSInterface Object
        WMPJSInterface = window.parent.WMPJSInterface;

        // save a reference to "SEND" Button
        this.send_btn = jQuery('#'+this.type+'_send_btn',this.DOMDoc).get(0);
       // add actions to send, cancel, ... buttons
        this.addButtonsActions();

        
    }




	/*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION ADD BUTTONS ACTIONS                                     */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.addButtonsActions = function(){

        /*******************************************************/
        /*                     SEND "BUTTON"                   */
        /*******************************************************/
        jQuery(this.send_btn).unbind("click");
        jQuery(this.send_btn).bind("click",function(){
            JSObject.disableButton(this);
            JSObject.sendData();
        })
        JSObject.enableButton(this.send_btn);

    }


    /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                 FUNCTION ENABLE BUTTON                                            */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.enableButton = function(btn){
        jQuery(btn).css('cursor','pointer');
        jQuery(btn).animate({opacity:1},100);
    }


    /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                 FUNCTION DISABLE BUTTON                                           */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.disableButton = function(btn){
        jQuery(btn).unbind("click");
        jQuery(btn).animate({opacity:0.4},100);
        jQuery(btn).css('cursor','default');
    }

    
	
	/*****************************************************************************************************/
	/*                                                                                                   */
	/*                          FUNCTION SEND DATA   								                     */          
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.sendData = function(){
		
		var isConfirmed = confirm("Are you sure you want to disconnect from Appticles?");
	
		if (isConfirmed) {
		
			WMPJSInterface.Preloader.start();
			
			jQuery.ajax({
				url: JSObject.submitURL,
				type: 'get',
				data: { 
					'apiKey':    jQuery("#"+JSObject.type+"_apikey", JSObject.DOMDoc).val()
				},
                dataType: "jsonp",
				success: function(responseJSON){
				    
                    WMPJSInterface.Preloader.remove(100);
					
					JSON = eval (responseJSON);
					response = Boolean(Number(String(JSON.status)));
					
					if (response == 0) {
						
                        if (JSON.message != undefined) {
                            
                            WMPJSInterface.Loader.display({message: JSON.message});
                            
                        } else {
                            
                            var message = 'We were unable to disconnect your plugin, please contact support.';
						    WMPJSInterface.Loader.display({message: message});
                        }	
                        
						//enable buttons
						JSObject.addButtonsActions();
					
					} else { 
					   											   
						jQuery.post(
							ajaxurl, 
							{
								'action': 'wmp_premium_disconnect',
								'api_key': jQuery("#"+JSObject.type+"_apikey", JSObject.DOMDoc).val(),
								'active': '0'
							}, 
							function(response1){
								response1 = Boolean(Number(String(response1)));
                                
								if(response1 == 1)
									window.location.href = JSObject.redirectTo;
								else {
									var message = 'There was an error. Please reload the page and try again in few seconds or contact the plugin administrator if the problem persists.';
									WMPJSInterface.Loader.display({message: message});	
								}
							}
						);	
					}
							
				},
				error: function(responseJSON){
				    
                     // API endpoint is turned off
                    WMPJSInterface.Preloader.remove(100);
                    WMPJSInterface.Loader.display({message: "Disconnect endpoint is unreachable. Please contact support."});
				}
			});
		}
		
	}

}