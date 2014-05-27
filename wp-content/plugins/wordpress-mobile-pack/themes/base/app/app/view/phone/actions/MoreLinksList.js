Ext.define("WP.view.phone.actions.MoreLinksList", {
  	extend: 'Ext.List',
	
	requires: [
		'WP.view.phone.actions.CreditsPanel',
		'WP.store.MoreLinks'
	],
	
	config: {
		itemId: 'moreLinksList',
		
		// custom properties
		
		// css properties
		cls: 'more',
		itemCls: '',
		selectedCls: '',
		pressedCls: 'item-pressed',
		height: 100,
				
		// properties
		scrollable: {
			direction: 'none',
        },
		disableSelection: true,
		useSimpleItems: true,
		itemTpl: new Ext.XTemplate(
			'<div class="icon {cls}"></div>',
			'<div class="details">',
				'{title}',
			'</div>'
		)	
	},
	
	initialize: function(){
		this.setStore(Ext.create("WP.store.MoreLinks"));
		this.callParent(arguments);
	}
});
