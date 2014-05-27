Ext.define("WP.view.phone.Main", {
    extend: 'Ext.Panel',
    
	requires: [
		'WP.store.Categories',
		'WP.view.phone.categories.CategoriesPanel',
		'WP.view.phone.articles.ArticlesPanel',
	],
   
	config: {
		
		id: 'mainView',
		
		// custom properties
		
		// css properties
		cls: 'main',
		
		// properties
		layout: 'card',
    },

    initialize: function(){
		
		// categories panel
		var categoriesPanel = Ext.create("WP.view.phone.categories.CategoriesPanel");
		this.add(categoriesPanel);
		
		// articles panel
		var articlesPanel = Ext.create("WP.view.phone.articles.ArticlesPanel");
		this.add(articlesPanel);

        // add events
		this.on("closepanel", this.onClosePanel, this);
        this.on("openpanel", this.onOpenPanel, this);
				
		this.callParent(arguments);
    },
	
	onOpenPanel: function(){
		var translateValue = -225;
		var time = 0.6;
		
        this.setStyle({
            '-webkit-transition': 'all '+time+'s ease',
            '-moz-transition': 'all '+time+'s ease',
            '-o-transition': 'all '+time+'s ease',
            'transition': 'all '+time+'s ease',
            '-webkit-transform': 'translate3d(' + translateValue + 'px, 0px, 0px)',
            '-moz-transform': 'translate3d(' + translateValue + 'px, 0px, 0px)',
            '-ms-transform': 'translate3d(' + translateValue + 'px, 0px, 0px)',
            '-o-transform': 'translate3d(' + translateValue + 'px, 0px, 0px)',
            'transform': 'translate3d(' + translateValue + 'px, 0px, 0px)'
        });
	},
	
	onClosePanel: function(){
		var time = 0.4;
		
        this.setStyle({
            '-webkit-transition': 'all '+time+'s ease',
            '-moz-transition': 'all '+time+'s ease',
            '-o-transition': 'all '+time+'s ease',
            'transition': 'all '+time+'s ease',
            '-webkit-transform': 'translate3d(0px, 0px, 0px)',
            '-moz-transform': 'translate3d(0px, 0px, 0px)',
            '-ms-transform': 'translate3d(0px, 0px, 0px)',
            '-o-transform': 'translate3d(0px, 0px, 0px)',
            'transform': 'translate3d(0px, 0px, 0px)'
        });
	}
});
