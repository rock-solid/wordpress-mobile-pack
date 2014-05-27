Ext.define('WP.proxy.Comments', {
    extend: 'Ext.data.proxy.JsonP',
    
	    
    config: {
        // This is the url we always query when searching for comments
        url: appticles.exportPath + 'content.php?content=exportcomments',
		
        reader: {
            type: 'json',
            rootProperty: 'comments'
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
            
			if (filter.articleId){
				Ext.apply(params, {
					articleId: filter.articleId
				});
			}
			
			this.setExtraParams(params);
		}
		
		var request = this.callParent(arguments);
            
		return request;
    },
	
	processResponse: function(success, operation, request, response, callback, scope){
		this.callParent([success, operation, request, response, callback, scope]);
	}
});