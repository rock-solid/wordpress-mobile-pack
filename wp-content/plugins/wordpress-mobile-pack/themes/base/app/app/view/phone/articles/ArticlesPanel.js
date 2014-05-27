Ext.define("WP.view.phone.articles.ArticlesPanel", {
    extend: 'Ext.Panel',
	
	requires: [
		'Ext.data.ArrayStore',
		'WP.view.phone.articles.ArticleCard',
		'WP.view.phone.articles.comments.CommentsBtn',
		'WP.view.phone.articles.comments.CommentsPanel'
	],
	
	config: {
		
		id: "articlesPanel",
		
		// custom properties
		store: null,												// articles store
		landscape: {												// available landscape space (width and height) for article 
			innerWidth: 0,
			innerHeight: 0
		},
		portrait: {													// available landscape space (width and height) for article 
			innerWidth: 0,
			innerHeight: 0
		},
		
		// css properties
		cls: "articles-panel",
				
		// properties
		layout: {
			type: 'card'
		},
		items: [
			{
				xtype: 'backbutton'
			}
		]
    },
	
	initialize: function(){
		
		var commentsBtn = Ext.create("WP.view.phone.articles.comments.CommentsBtn");
		this.add(commentsBtn);
		
		// create a store with details about displayed articles
		this.setStore(Ext.create("Ext.data.ArrayStore", {
			fields: [
				{name: 'id',  					type: 'string'},									// article id
				{name: 'category_id',  			type: 'string'},									// category id
			]
		}));
		
		
		// add a handler for the orientationchange event of the viewport
		Ext.Viewport.on('orientationchange', 'handleOrientationChange', this, {buffer: 50 });
		
		this.callParent(arguments);
	},
	
	
	// setters and getters used for sizes for both orientation types
	setInnerWidth: function(value){
		this.config[this.getOrientation()].innerWidth = value;
	},
	setInnerHeight: function(value){
		this.config[this.getOrientation()].innerHeight = value;
	},
	getInnerWidth: function(){
		return this.config[this.getOrientation()].innerWidth;
	},
	getInnerHeight: function(){
		return this.config[this.getOrientation()].innerHeight;
	},
	
	
	handleOrientationChange: function(){
		
		var mainView = this.up("#mainView");
		
		if (mainView.getActiveItem() == this){
			// add mask
			var mask = Ext.create("WP.view.LoadMask");
			Ext.Viewport.setMasked(mask);
		}
		
		var me = this;
		
		Ext.defer(function(){
						
			// fire orientation event only for the current carousel (the visible one), and for the rest clear cards
			for (var i=me.getItems().length-1; i>=0; i--){
				var articleCard = me.getAt(i).getRecord() ? me.getAt(i) : null;
				
				if (articleCard){
					articleCard.setIsFilled(false);
					articleCard.fireEvent("repaint");
					articleCard.fireEvent("addcontent");
				}
			}
		
		
			if (mainView.getActiveItem() == me){
				mask.hide();
			}
		}, 50, this);
	},
	
	
	/* on Android Tablets (al least on 4.0 version) "landscape" and "portrait" are reversed
	   because the detection is made on window orientation property and not on window screen sizes
	   the function fixes that */
	getOrientation: function(){
		if (!Ext.os.is.Android){
			return 	Ext.Viewport.getOrientation();
		}
		
		return Ext.Viewport.getWindowWidth() > Ext.Viewport.getWindowHeight() ? "landscape" : "portrait";
	}
});
