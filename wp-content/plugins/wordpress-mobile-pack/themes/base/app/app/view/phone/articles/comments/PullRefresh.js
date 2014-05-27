Ext.define('WP.view.phone.articles.comments.PullRefresh', {
    extend: 'Ext.plugin.PullRefresh',
    
	requires: [
      	
    ],
    
	config: {
        itemId: 'pullRefresh',
		pullText: 'Pull down for more new comments!',
        loadingText: 'Loading ...',
        releaseText: 'Last Updated'+':&nbsp;',
        lastUpdatedText: 'Release to refresh...',
		
		lastUpdatedDateFormat: 'd/m/Y h:iA',
		cls: 'pullrefresh'
	},
	
	initialize: function(){
		
		var container = this.element.dom;
        
        Ext.get(container).setStyle("width","100%");
		
		this.callParent(arguments);
	},
	
	fetchLatest: function(){
		var self = this;
		var list = this.getList();
		
		list.fireEvent("fetchlatest", {
			callback: function(operation){ 
				self.onLatestFetched(); 
			}
		});
	},
	
	onLatestFetched: function(){
		this.setState("loaded");
        if (this.getAutoSnapBack()) {
            this.initScrollable();
			this.snapBack();
        }	
	}
});
