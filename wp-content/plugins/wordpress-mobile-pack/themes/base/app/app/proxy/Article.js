Ext.define('WP.proxy.Article', {
    extend: 'Ext.data.proxy.JsonP',
    
	config: {
        // This is the url we always query when searching for posts
        //url: appticles.exportPath+'exportarticle?webApp='+appticles.webApp,
		url: appticles.exportPath + 'content.php?content=exportarticle',
		
		model: "WP.model.Article",
		
		reader: {
            type: 'json',
            rootProperty: 'article'
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
		
		if (response && response.article){
			
			// process article before adding to the store
			var article = articlesController.processArticles(response.article);
			
			this.callParent([success, operation, request, [article], callback, scope])
		}
		else{
			this.callParent([success, operation, request, response, callback, scope])	
		}
	}
});