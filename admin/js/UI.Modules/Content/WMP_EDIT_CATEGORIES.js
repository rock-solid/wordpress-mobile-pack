/*****************************************************************************************************/
/*                                                                                                   */
/*                                    	'ACTIVATE / DEACTIVATE CATEGORIES'                           */
/*                                                                                                   */
/*****************************************************************************************************/

function WMP_EDIT_CATEGORIES(){

    var JSObject = this;

    this.type = "wmp_editcategories";

    this.form;
    this.DOMDoc;
    
    this.changingStatus = false;

	/*****************************************************************************************************/
    /*                                                                                                   */
    /*                              FUNCTION INIT - called from WMPJSInterface                              */
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

        this.initCategories();
		
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
	    
    	jQuery( "ul.categories", this.form ).sortable( {  update: function(event, ui) { JSObject.changeOrder(); } } );
        
        // sorting will be disabled if we have less than 2 feeds in the list
        this.update();
		
    }


    /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION INIT VALIDATION                                         */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.initCategories = function(){

        /*****************************************************************************************************/
	    /*                                                                                                   */
	    /*                                	CHANGE STATUS ITEM ACTIONS                                		 */
	    /*                                                                                                   */
	    /*****************************************************************************************************/

        jQuery("li div.row", this.form).on("click", function(){
		
			var isConfirmed = confirm("Are you sure you want to change the status for this category?");
	
			if (isConfirmed) {

				var currentStatus;
                
                var statusContainer = jQuery('.status',this);
                var categoryId = jQuery(this).parent().attr("data-category-id");
                
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
                            'action': 'wmp_content_status',
                            'id':   categoryId,
                            'status': currentStatus,
                            'type': 'category'
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
                                var no_active_categories = jQuery( "li span.active", JSObject.form ).length;
                                if (no_active_categories > 0){
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
		});
        
        // close button action for the inactive categories warning
        jQuery( "#" + JSObject.type + "_warning a.close-x", this.form ).on("click", function(){
            jQuery('#'+JSObject.type+'_warning', JSObject.DOMDoc).hide();
        })
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
    	
    	// build string with the categoriess order
    	jQuery( "ul.categories li", JSObject.form).each(function(index, object){
    	
    		stringOrder += jQuery(this).attr("data-category-id") + ",";
    		
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
				'type'	: 'categories',
				'ids':   stringOrder
			},
			function(response){
				
                WMPJSInterface.Preloader.remove(100);
  		        JSObject.changingStatus = false;
                
			 	var response = Boolean(Number(String(response)));
			 
				if (response == true) {
				
					// success message								
					var message = 'The order of the categories has been successfully changed.';
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
    	
    	var noCategories= jQuery("ul.categories li", this.form).length;
        
        if (noCategories > 1) {
    		
    		// enable list ordering
    		jQuery( "ul.categories", JSObject.form ).sortable("enable")
    			
    	} else {
    		
    		// disable list ordering
    		jQuery( "ul.categories", JSObject.form ).sortable("disable")
    	}
    }

}