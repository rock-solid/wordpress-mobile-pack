/*
    This file is generated and updated by Sencha Cmd. You can edit this file as
    needed for your application, but these edits will have to be merged by
    Sencha Cmd when it performs code generation tasks such as generating new
    models, controllers or views and when running "sencha app upgrade".

    Ideally changes to this file would be limited and most work would be done
    in other places (such as Controllers). If Sencha Cmd cannot merge your
    changes and its generated code, it will produce a "merge conflict" that you
    will need to resolve manually.
*/

Ext.application({
    name: 'WP',

    requires: [
        'Ext.MessageBox',
		'WP.util.Viewport'
    ],

    profiles: 		['Tablet', 'Phone'],
	controllers:	['Main','Categories', 'Articles', 'Actions'],
	
    icon: {
        '40': appticles.icon,
		'50': appticles.icon,
		'57': appticles.icon,
		'60': appticles.icon,
        '72': appticles.icon,
		'76': appticles.icon,
		'80': appticles.icon,
        '114': appticles.icon,
		'120': appticles.icon,
        '144': appticles.icon,
		'152': appticles.icon
    },

    isIconPrecomposed: true,

    /*startupImage: {
        '320x460': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/320x460.png',
		'320x480': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/320x480.png',
        '640x920': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/640x920.png',
		'640x960': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/640x960.png',
		'640x1136': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/640x1136.png',
        '768x1004': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/768x1004.png',
		'768x1024': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/768x1024.png',
        '748x1024': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/748x1024.png',
        '1496x2048': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/1496x2048.png',
		'1536x2008': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/1536x2008.png',
        '1536x2048': ((appticles.hasStartups) ? appticles.appPath : appticles.defaultPath) +'resources/startup/1536x2048.png'
    },*/

    launch: function() {
        // Destroy the #appLoadingIndicator element
        Ext.fly('appLoadingIndicator').destroy();
		
		// a hack used for Android (4+) browsers, to handle the Viewport's orientation change event
		Ext.Viewport.bodyElement.on('resize', Ext.emptyFn, this, { buffer: 1});

        // save current profile
		appticles.profile = this.getCurrentProfile().getName().toLowerCase();
    },

    onUpdated: function() {
        Ext.Msg.confirm(
            "Application Update",
            "This application has just successfully been updated to the latest version. Reload now?",
            function(buttonId) {
                if (buttonId === 'yes') {
                    window.location.reload();
                }
            }
        );
    }
});
