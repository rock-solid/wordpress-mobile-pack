Ext.define("WP.store.Comments", {
    extend: 'Ext.data.Store',
	
	requires:[
		'WP.model.Comment',
		'WP.proxy.Comments'
	],
		    
	config: {
        model: 'WP.model.Comment',
		clearOnPageLoad: true,
		pageSize: 500
    },
	
	initialize: function(){
		var proxy = Ext.create("WP.proxy.Comments");
		this.setProxy(proxy);
	}
});
