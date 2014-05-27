Ext.define("WP.store.Categories", {
    extend: 'Ext.data.Store',
	
	requires: [
		'WP.model.Category',
		'WP.proxy.Categories'
	],
		    
	config: {
        model: 'WP.model.Category',
		sorters: {
			property : 'order',
			direction: 'ASC'
		}
    },
	
	
	initialize: function(){
		var proxy = Ext.create("WP.proxy.Categories");
		this.setProxy(proxy);
	}
});
