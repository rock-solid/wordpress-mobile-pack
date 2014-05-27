Ext.define('WP.profile.Phone', {
    extend: 'Ext.app.Profile',
	
	requires: [
		"WP.view.phone.actions.ActionsPanel"
	],
		
    config: {
        name: 'Phone',
       
	  	views: ['Main'],
	},

    isActive: function() {
       	return Ext.os.is.Phone;// || Ext.os.is.Desktop;
	},
	
	launch: function(){
		
		// Initialize the main view
      	Ext.Viewport.add(Ext.create('WP.view.phone.actions.ActionsPanel', {zIndex: 1}));
		
		// Initialize the main view
      	Ext.Viewport.add(Ext.create('WP.view.phone.Main', {zIndex: 1}));
	}
});