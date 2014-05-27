Ext.define('WP.proxy.Categories', {
    extend: 'Ext.data.proxy.JsonP',
    
	    
    config: {
        // This is the url we always query when searching for posts
        //url: appticles.exportPath+'exportcategories?webApp='+appticles.webApp,
		url: appticles.exportPath + 'content.php?content=exportcategories',
		
        reader: {
            type: 'json',
            rootProperty: 'categories'
        }
    },


    /**
     * We need to add a slight customization to buildRequest - we're just checking for a filter on the
     * Operation and adding it to the request params/url, and setting the start/limit if paging
     */
    buildRequest: function(operation) {
      	
		var filter  = operation.getFilters(),
            params  = new Object();
		
		
		if (filter) {
            
			if (filter.limit){
				Ext.apply(params, {
					limit: filter.limit
				});
			}
			
			if (filter.descriptionLength){
				Ext.apply(params, {
					descriptionLength: filter.descriptionLength
				});
			}
			
			this.setExtraParams(params);
		}
		
		var request = this.callParent(arguments);
            
		return request;
    },
	
	
	processResponse: function(success, operation, request, response, callback, scope){
		
		if (response && response.categories && response.categories.length > 0){
			
			// process categories and them articles before adding to the store
			var categories = categoriesController.processCategories(response.categories);
			
			this.callParent([success, operation, request, categories, callback, scope]);
		}
		else{
			this.callParent([success, operation, request, response, callback, scope]);	
		}
	}
});