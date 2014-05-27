Ext.define('WP.proxy.Articles', {
    extend: 'Ext.data.proxy.JsonP',
    	    
    config: {
        // This is the url we always query when searching for posts
        //url: appticles.exportPath+'exportarticles?webApp='+appticles.webApp,
		url: appticles.exportPath + 'content.php?content=exportarticles',
		
        reader: {
            type: 'json',
            rootProperty: 'articles'
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
			
			if (filter.categoryId != null){
				Ext.apply(params, {
					categoryId: filter.categoryId
				});
			}
			
			if (filter.lastTimestamp){
				Ext.apply(params, {
					lastTimestamp: filter.lastTimestamp
				});
			}
			
			this.setExtraParams(params);
		}
		
		var request = this.callParent(arguments);
            
		return request;
    },
	
	
	processResponse: function(success, operation, request, response, callback, scope){
		
		if (response && response.articles && response.articles.length > 0){
			
			// process articles before adding to the store
			var articles = articlesController.processArticles(response.articles);
			
			this.callParent([success, operation, request, articles, callback, scope])
		}
		else{
			this.callParent([success, operation, request, response, callback, scope])	
		}
	}
});