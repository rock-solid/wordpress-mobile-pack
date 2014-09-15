/*****************************************************************************************************/
/*                                                                                                   */
/*                                    	'ACTIVATE / DEACTIVATE PAGES'                                */
/*                                                                                                   */
/*****************************************************************************************************/

function WMP_EDIT_PAGES(){

    var JSObject = this;

    this.type = "wmp_editpages";

    this.form;
    this.DOMDoc;
    
    this.changingStatus = false;
	this.editPageDialogWindow;
	this.changingOrder = false;
	
	/*****************************************************************************************************/
    /*                                                                                                   */
    /*                              FUNCTION INIT - called from WMPJSInterface                           */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.init = function(){

        // save a reference to WMPJSInterface Object
        WMPJSInterface = window.parent.WMPJSInterface;

        // save a reference to the FORM and remove the default submit action
        this.form = this.DOMDoc.getElementById(this.type+'_form');

        if (this.form == null){
            return;
        }

        this.initPages();
		
		 // custom list actions (sortable)
        this.initListActions();
    }


	/*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION INIT LIST ACTIONS                                		 */
    /*                                                                                                   */
    /*****************************************************************************************************/
    
    this.initListActions = function(){
    	
    	/*****************************************************************************************************/
	    /*                                                                                                   */
	    /*                                		 ATTACH SORTABLE BEHAVIOUR                         			 */
	    /*                                                                                                   */
	    /*****************************************************************************************************/
	    
    	jQuery( "ul.pages", this.DOMDoc ).sortable( {  update: function(event, ui) { JSObject.changeOrder(); } } );
        
        // sorting will be disabled if we have less than 2 feeds in the list
        this.update();
				
		// attach edit actions for each feed
		jQuery( "ul.pages li div.row", this.DOMDoc ).on("click", JSObject.changeStatus);
		
    }
	


    /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION INIT VALIDATION                                         */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.initPages = function(){
 
 		// sort list elements
 		/*var pagesContainer =  jQuery( "ul.pages", this.DOMDoc );
		var pagesItems = pagesContainer.children('li').get();
 		
		pagesItems.sort(function(a, b) {
							var orderA = jQuery(a).attr("data-order");
							var orderB = jQuery(b).attr("data-order");
							if (orderA < orderB) return -1;
							if (orderA > orderB) return 1;
							return 0;
						});
 
 		jQuery(pagesContainer).append(pagesItems);
		*/
 
        // close button action for the inactive categories warning
        jQuery( "#" + JSObject.type + "_warning a.close-x", this.form ).on("click", function(){
            jQuery('#'+JSObject.type+'_warning', JSObject.DOMDoc).hide();
        })
    }
	
	/*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION INIT VALIDATION                                         */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.changeStatus = function(){

		var pageId = jQuery(this).closest("li").attr("data-page-id");
        var Container = jQuery(this).closest("li");

        /*****************************************************************************************************/
	    /*                                                                                                   */
	    /*                                	CHANGE STATUS ITEM ACTIONS                                		 */
	    /*                                                                                                   */
	    /*****************************************************************************************************/
	    
		
		var isConfirmed = confirm("Are you sure you want to change the status for this page?");

		if (isConfirmed) {

			var currentStatus;
			
			var statusContainer = jQuery('.status',Container);
			
			if (statusContainer.hasClass("active") == false) {
				currentStatus = "active";	
			} else {
				currentStatus = "inactive";
			}
				
			if (JSObject.changingStatus == false) {
				
				WMPJSInterface.Preloader.start();
				
				jQuery.post(
					ajaxurl, 
					{
						'action': 'wmp_content_pagestatus',
						'id':   pageId,
						'status': currentStatus
					}, 
					function(response){
						
						JSObject.changingStatus = false;
						WMPJSInterface.Preloader.remove(100);
						
						var response = Boolean(Number(String(response)));
					 
						if (response == true) {
						
							// change status class and text
							statusContainer.addClass(currentStatus);
							statusContainer.removeClass(currentStatus == 'active' ? 'inactive' : 'active');
							
							statusContainer.text(currentStatus);
							
							// success message								
							var message = 'The status of this category has been changed.';
							WMPJSInterface.Loader.display({message: message});
							
							// count remaining active categories
							var no_active_pages = jQuery( "li span.active", JSObject.form ).length;
							if (no_active_pages > 0){
								jQuery('#'+JSObject.type+'_warning', JSObject.DOMDoc).hide();
							} else {
								jQuery('#'+JSObject.type+'_warning', JSObject.DOMDoc).show();
							}
							
						} else {
						
							// error message
							var message = 'There was an error. Please reload the page and try again in few seconds or contact the plugin administrator if the problem persists.';
							WMPJSInterface.Loader.display({message: message});	
							
						}
					}
				);
			}
		}
    }
	
	
	
	/*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION CHANGE ORDER                                      		 */
    /*                                                                                                   */
    /*****************************************************************************************************/
    
    this.changeOrder = function(){
    	
        if (JSObject.changingStatus == true)
            return;
            
    	var stringOrder = '';
    	
    	// build string with the pages order
    	jQuery( "ul.pages li", JSObject.DOMDoc).each(function(index, object){
    	
    		stringOrder += jQuery(this).attr("data-page-id") + ",";
    		
    		var newIndex = index + 1;
            jQuery(this).attr("data-order", newIndex)
    	});
        
    	// -------------------------------------- //
        WMPJSInterface.Preloader.start();
        JSObject.changingStatus = true;
        
    	// make ajax request
    	jQuery.post(
			ajaxurl, 
			{
				'action': 'wmp_content_order',
				'type'	: 'pages',
				'ids':   stringOrder
			},
			function(response){
                WMPJSInterface.Preloader.remove(100);
  		        JSObject.changingStatus = false;
                
			 	var response = Boolean(Number(String(response)));
			 
				if (response == true) {
				
					// success message								
					var message = 'The order of the pages has been successfully changed.';
					WMPJSInterface.Loader.display({message: message});
				
				} else {
				
                    // error message
					var message = 'There was an error. Please reload the page and try again in few seconds or contact the plugin administrator if the problem persists.';
					WMPJSInterface.Loader.display({message: message});	
				}
			}
		)
    }
	
	
	 /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION UPDATE LIST  		                                   	 */
    /*                                                                                                   */
    /*****************************************************************************************************/
    
    this.update = function(){
    	
    	var noPages = jQuery("ul.pages li", this.DOMDoc).length;
        
        if (noPages > 1) {
    		
    		// enable list ordering
    		jQuery( "ul.pages", JSObject.DOMDoc ).sortable("enable")
    			
    	} else {
    		
    		// disable list ordering
    		jQuery( "ul.pages", JSObject.DOMDoc ).sortable("disable")
    	}
    }
   

}