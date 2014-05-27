Ext.define("WP.view.BackButton", {
    extend: 'Ext.Button',
	xtype: 'backbutton',
	   
	config: {
		
		// custom properties
		action: 'back',
		
		// css properties
		cls: 'back-button',
		iconCls: 'back',
        pressedCls: 'pressed',
		top: 0,
		left: 0,
		height: 60,
		width: 60,
	
		
		// properties
        html: '&nbsp;'
	},

	initialize: function(){
        this.callParent(arguments);
	}
});
