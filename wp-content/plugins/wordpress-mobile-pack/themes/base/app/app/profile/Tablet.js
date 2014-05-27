Ext.define('WP.profile.Tablet', {
    extend: 'Ext.app.Profile',
	
	requires: [
		
	],

    config: {
        name: 'Tablet',
       
		views: ['Main'],
	},
	
	isActive: function() {
       	return Ext.os.is.Tablet || Ext.os.is.Desktop;
	},
	
    launch: function(){
		
		// Initialize the main view
      	Ext.Viewport.add(Ext.create('WP.view.tablet.Main'));
	}
});