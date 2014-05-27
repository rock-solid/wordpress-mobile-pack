Ext.define("WP.store.MoreLinks", {
    extend: 'Ext.data.Store',
	requires: [
        'WP.model.MoreLinks',
    ],
	
	config: {
       	model: 'WP.model.MoreLinks',
		
        data: [
			{
				title: "View desktop version",
				cls: "website",
			},
			{
				title: "Credits",
				cls: "credits",
			}
		]	
    }
});
