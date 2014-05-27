Ext.define("WP.view.phone.actions.ActionsPanel", {
    extend: 'Ext.Panel',

    requires: [
        'WP.view.phone.actions.Categories',
        'WP.view.phone.actions.MoreLinksList'
    ],
   
	config: {
		
		itemId: 'actionsPanel',
        
		// custom properties
		
		// css properties
		cls: 'actions-panel',
		top: 0,
		right: 0,
		width: 225,
        height: '100%',
		layout: {
			type: 'vbox',
			pack: 'start',
			align: 'justify'
 		},
		
		// properties
		scrollable: {
			direction: 'vertical',
            indicators: false
		},
		items: [ 
			{
				xtype: "component",
				html: "<h1>Categories</h1>",
				height: 45,
				docked: "top",
				cls: "categories"	
			}
		],
		hidden: true
	},

    initialize: function(){
		
		this.callParent(arguments);
		
		this.add([
            Ext.create('WP.view.phone.actions.Categories'),
            Ext.create('WP.view.phone.actions.MoreLinksList')
        ]);
	}
});
