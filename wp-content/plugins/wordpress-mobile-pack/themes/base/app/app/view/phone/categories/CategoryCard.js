Ext.define("WP.view.phone.categories.CategoryCard", {
    extend: 'Ext.Panel',
	
	requires: [
		'WP.view.phone.categories.CategoryTemplates',
		'WP.view.phone.categories.CategoryTemplates1',
		'WP.view.phone.categories.CategoryTemplates2',
	],
	
	config: {
		
		name: "categoryCard",
		
		// custom properties
		store: null,							// articles store
		categoryId: -1,
		categoryName: "",
		index: 0,								// this is the index of the card in the category order
		templates: [1,1],						// an array with the number of templates for each type of the card 
												// (Ex: for a card with 4 articles there are 5 different templates)
		layoutType: -1,							// for every card there are several ways to display articles based on CSS. 
												// Ex: There can be 5 ways of arranging 4 items on a card.
		isFilled: false,						// a flag indicates if the card was filled with content
		noOfArticles: 0,						// the number of articles contained by this card
		carousel: null,
		articlesBox: null,
		pressedTimeout: null,					// a delay used for changing the class of the pressed item on this card
		
		// css properties
		cls: 'card',
		itemCls: 'article',
		pressedCls: 'pressed',
		layout:{
			type: 'vbox',
			pack: 'start',
			align: 'stretch'
		},
		
		// properties
		scrollable: null,
		items: [
			{
				xtype: 'component',
				itemId: 'articlesBox',
				flex: 1,
			},
			{
				xtype: "button",
				action: 'view-actions-panel',
				iconCls: 'menu',
				html: '&nbsp;',
				cls: 'actions-panel-button',
				pressedCls: 'pressed',
				width: 60,
				height: 60,
				top: 0,
				right: 0
			}
		]
	},
	
	
	initialize: function(){
		
		//var index = (this.getNoOfArticles() == 3) ? 2 : 1;
		var noOfTemplates = this.getTemplates()[this.getNoOfArticles()-1];
		
		var rand;
		// generate a random number representing a template
		do{
			rand = Math.random();		
		}
		while (rand < 0 || rand >= 1)
		
		var index = (this.getLayoutType() == -1) ? 1 + Math.floor(rand*noOfTemplates) : this.getLayoutType();
		
		this.setItemCls(this.getItemCls()+" t"+index);
		this.setLayoutType(index);
		
		
		var articlesBox = this.down("#articlesBox");
		this.setArticlesBox(articlesBox);
		
		// change card CSS class according to the number of articles contained 
		articlesBox.setCls("articles-"+this.getNoOfArticles());
		
		// create the articles store
		this.setStore(Ext.create("Ext.data.Store", {
			model: 'WP.model.Article'										
		}));
						
		// set a template for this card
		this.on('settemplate', this.onSetTemplate, this);
		this.on('setactions', this.onSetActions, this);
				
		this.fireEvent("settemplate");
		this.fireEvent("updatebar");
		
		this.callParent(arguments);
	},
	
	onSetTemplate: function(){
		var carousel = this.getCarousel() || this.getParent();
		var articlesBox = this.getArticlesBox();
		
		// generate a new template according with the card layout type
		var tpl = Ext.create("WP.view.phone.categories.CategoryTemplates"+this.getNoOfArticles(), {
			index: this.getLayoutType(),
			carousel: carousel,
			card: this,
			itemCls: this.getItemCls()
		});
		articlesBox.setTpl(tpl);
	},
	
	
	onSetActions: function(){
		var articlesBox = this.getArticlesBox();
		
		// attach tap event for each article items on this card
		var articleItems = articlesBox.element.query("div[class^=article]");
		
		for (var k=0; k<articleItems.length; k++){
			var articleItem = Ext.get(articleItems[k]);
			articleItem.on("tap", this.onArticleItemTap, this);
			articleItem.on("touchstart", this.onArticleItemTouchStart, this);
			articleItem.on("touchend", this.onArticleItemTouchMove, this);
		}
	},
	
	
	onArticleItemTap: function(event, item){
		
		var articleItem = Ext.get(item).hasCls("article") ? Ext.get(item) : Ext.get(item).up("div[class^=article]");
		var articleId = articleItem.getAttribute("data-article-id");
		
		// redirect to article details page
		WP.app.redirectTo("article/"+articleId);
	},
	
	
	onArticleItemTouchStart: function(event, item){
		
		var articleItem = Ext.get(item).hasCls("article") ? Ext.get(item) : Ext.get(item).up("div[class^=article]");
		var articleId = articleItem.getAttribute("data-article-id");
		
		articleItem.un("touchmove", this.onArticleItemTouchMove);
		articleItem.on("touchmove", this.onArticleItemTouchMove, this);
		
		var me = this;
		this.setPressedTimeout(setTimeout(function(){
			// remove pressed item cls
			articleItem.addCls(me.getPressedCls());
		}, 100));
	},
	
	
	onArticleItemTouchMove: function(event, item){
		
		var articleItem = Ext.get(item).hasCls("article") ? Ext.get(item) : Ext.get(item).up("div[class^=article]");
		
		if (articleItem){
			var articleId = articleItem.getAttribute("data-article-id");
			
			delete this.getPressedTimeout();
			clearTimeout(this.getPressedTimeout());
			
			// remove pressed item cls
			articleItem.removeCls(this.getPressedCls());
			
			articleItem.un("touchmove", this.onArticleItemTouchMove);
		}
	}
});
