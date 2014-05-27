Ext.define("WP.view.phone.categories.CategoriesCarousel", {
    extend: 'Ext.Carousel',
	
	requires: [
		'Ext.data.reader.Array',
		'Ext.data.ArrayStore',
		'WP.view.phone.categories.Cover',
		'WP.view.phone.categories.CategoryCard',
	],
	
	config: {
		
		// custom properties
		mainView: null,												// a reference to mainView panel
		categoriesPanel: null,										// the parent of this carousel; it is givven when the carousel is created
		layoutsPath: [2,1,2,1],										// the order of succession of cards depending on the number of articles
		noOfDefaultItems: 0,
		landscape: {												// available landscape space (width and height) for articles 
			innerWidth: 0,
			innerHeight: 0
		},
		portrait: {													// available landscape space (width and height) for articles 
			innerWidth: 0,
			innerHeight: 0
		},
		
		// css properties
		cls: 'carousel',
				
		// properties
		indicator: false,
		animation: {
            duration: 600,
            easing: {
                type: 'ease-out'
            }
        },
	},
	
	
	initialize: function(){
		this.callParent(arguments);
		
		var categoriesPanel = this.getCategoriesPanel();
				
		// create cover card and add it to the begining of the carousel (only the first time)
		if (categoriesPanel.getLastCarousel() == null){
			this.onAddCover();
			this.setActiveItem(0);
		}
					
		this.setNoOfDefaultItems(this.getItems().length);
		
		// add a handler for the orientationchange event of the viewport
		Ext.Viewport.on('orientationchange', 'handleOrientationChange', this, {buffer: 50 });
		
		// set the innerWidth and innerHeight of the carousel
		this.on("addcover",this.onAddCover, this);
		this.on("setsizes",this.onSetSizes, this);
		this.on("activeitemchange",this.onActiveItemChange, this);
		this.on("buildcards",this.onBuildCards, this);
		this.on("removealllisteners", this.onRemoveAllListeners, this);
		
		this.fireEvent("setsizes");
	},
	
	handleOrientationChange: function(){

		// get mainView reference
		var mainView = this.getMainView();
		
		if (!mainView){
			this.setMainView(this.up("#mainView"));
			mainView = this.getMainView();
		}
		
		
		if (mainView.getActiveItem() == this.getParent()){
			// add mask
			var mask = Ext.create("WP.view.LoadMask");
			Ext.Viewport.setMasked(mask);
		}
			
		this.fireEvent("setsizes");
		
		Ext.defer(function(){
			// change all cards' templates according with the layout orientation
			for (var i=this.getNoOfDefaultItems(); i<this.getItems().length; i++){
				var card = this.getAt(i);
				card.fireEvent("settemplate");
				
				var articlesBox = card.getArticlesBox();
				var data = articlesBox.getData().concat([]);
				articlesBox.setData(data);
				card.fireEvent("setactions");
			}
			
			if (mainView.getActiveItem() == this.getParent()){
				mask.hide();
			}
		}, 200, this);
	},
	
	
	// setters and getters used for sizes for both orientation types
	setInnerWidth: function(){
		this.config[this.getOrientation()].innerWidth = document.body.clientWidth;
	},
	setInnerHeight: function(){
		this.config[this.getOrientation()].innerHeight = document.body.clientHeight;
	},
	getInnerWidth: function(){
		return this.config[this.getOrientation()].innerWidth;
	},
	getInnerHeight: function(){
		return this.config[this.getOrientation()].innerHeight;
	},
	
	
	onAddCover: function(){
		var noOfDefaultItems = this.getNoOfDefaultItems();
		
		var cover = Ext.create("WP.view.phone.categories.Cover");
		this.insert(noOfDefaultItems, cover);
		
		this.setNoOfDefaultItems(noOfDefaultItems+1);
	},
	
	onActiveItemChange: function(carousel, newCard, oldCard){
		
		// get mainView reference
		var mainView = this.getMainView();
		
		if (!mainView){
			this.setMainView(this.up("#mainView"));
			mainView = this.getMainView();
		}
		
		var categoriesPanel = this.getParent();
		var categoriesStore = mainController.categoriesStore;
		
		// the new card is the cover one
		if (newCard.getItemId() == "cover"){
			WP.app.redirectTo("");
			return;
		}
		else if (oldCard == null) return;
		
		var category = categoriesStore.findRecord("id", newCard.getCategoryId(), 0, false, true, true);
		var layouts = categoriesPanel.getStore().findRecord("id", newCard.getCategoryId(), 0, false, true, true);
		
		if (layouts == null) return;
		
		
		// if the new Card belongs to a different category from the current one, then change the history URL
		// or the old card is the cover card
		if (oldCard && (oldCard.getItemId() == "cover" || (newCard.getCategoryId() != oldCard.getCategoryId()))){
			
			// if the categories panel is active then redirect to new category URL
			if (mainView.getActiveItem() == categoriesPanel){
				
				var categoryName = category.get("name_slug").replace(/\s/g,"-");
				var categoryId = category.get("id");
				
				// redirect to the new category URL
				var lastCard = carousel.getActiveItem();
				WP.app.redirectTo("category/"+categoryName+"/"+categoryId);
				carousel.setActiveItem(lastCard);
			}
		}
		
		// verify if we must request new articles depending on the number of cards of the active category
		// if there are only 2 cards or less and no more hidden articles are available for drawing, make a request on server
		if (layouts.get("cardsIds").length - newCard.getIndex() <= 2){
			
			var articles = category.articles();
			var coverArticleId = this.down("#cover").getData()[0].id;
			var firstArticleId = articles.getAt(0).getData().id;
			var hiddenArticles = articles.getCount() - layouts.get("displayedArticlesIds").length - ((firstArticleId == coverArticleId) ? 1 : 0);
			console.log(hiddenArticles)
			if (hiddenArticles != 0){
				// build category's cards
				carousel.fireEvent("buildcards", category.get("id"));	
			}
			else if (!layouts.get("noMoreArticles")){
				console.log("load more articles")
				categoriesController.loadArticles({categoryId: category.get("id")});
			}
		}
		
		
		// if we are on the last card of the current category, request the next one with its articles
		if (layouts.get("noMoreArticles") && newCard.getIndex() == layouts.get("cardsIds").length-1){
			
			var index = categoriesStore.findExact("id", category.get("id"));
			
			// if there are available categories that must be show
			if (index + 1 <= categoriesStore.getCount() - 1){
				
				var nextCategory = categoriesStore.getAt(index+1);
								
				// verify first if the next available category was already created and add it if it was not created
				if (categoriesPanel.getStore().findExact("id", nextCategory.get("id")) == -1){
					// add category
					categoriesPanel.fireEvent("addcategory", nextCategory);
				}
				
				// build category's cards
				this.fireEvent("buildcards", nextCategory.get("id"));
			}
		}
	},
	
	
	onBuildCards: function(categoryId){
		// get reference
		var categoriesPanel = this.getParent();
		var categoriesStore = mainController.categoriesStore;
				
		// try to find the category from the store
		var category = categoriesStore.findRecord("id", categoryId, 0, false, true, true);
		var layouts = categoriesPanel.getStore().findRecord("id", categoryId, 0, false, true, true);
		
		// get articles for category
		var articles = category.articles();
		var totalArticles = articles.getCount();
		var coverArticleId = categoriesPanel.getCoverArticleId() || this.down("#cover").getData()[0].id;
		var firstArticleId = articles.getAt(0).getData().id;
		var displayedArticles = layouts.get("displayedArticlesIds").length;
		categoriesPanel.setCoverArticleId(coverArticleId);
				
		// number of articles to show on different layouts
		var dif = totalArticles - displayedArticles - ((firstArticleId == coverArticleId) ? 1 : 0);
		console.log(totalArticles, displayedArticles, dif)	
		// there are new articles to show
		if (dif != 0){
			var layoutsPath = this.getLayoutsPath();
			var currentLayouts = new Array().concat(layouts.get("cardsLayout"));
			var currentCssLayouts = new Array().concat(layouts.get("cardsCssLayout"));
			var currentCardsIds = new Array().concat(layouts.get("cardsIds"));
			var currentDisplayedArticlesIds = new Array().concat(layouts.get("displayedArticlesIds"));
			var pages = [];
			var nextLayoutIndex = layouts.get("nextLayoutIndex");
			var nextLayout = layoutsPath[nextLayoutIndex];
			
			// distribute articles according to the cards layout
			switch (dif){
				 
				case 1: 
					//layouts.set("noMoreArticles", true);
					nextLayout = 1;
					pages.push(nextLayout);
					dif -= nextLayout;
					break;
				
				default: 
					var i=nextLayoutIndex;
					while (dif > 0 && pages.length < 3){
						if (dif - nextLayout >= 0){
							pages.push(nextLayout);
							dif -= nextLayout;
						}
						else if (dif == 1){
							//layouts.set("noMoreArticles", true);	
							nextLayout = 1;
							pages.push(nextLayout);
							dif -= nextLayout;
						}
						i++;
						if (i > layoutsPath.length-1){
							i = 0;	
						}
						nextLayout = layoutsPath[i];
						nextLayoutIndex = i;
					}
					break;
			}
			console.log(pages, nextLayoutIndex)
			// for each new page create a card
			for (var i=0; i<pages.length; i++){
				//console.log("i: "+i);
				var noOfArticles = pages[i];
				var index = currentLayouts.length;
				var itemId = categoryId + "_" + index;
							
				// add a new card
				var card = Ext.create('WP.view.phone.categories.CategoryCard', {
					index: index,
					categoryId: categoryId,
					categoryName: category.get("name"),
					noOfArticles: noOfArticles,
					itemId: itemId,
					layoutType: currentCssLayouts[index] || -1,
					carousel: this
				});
				
								
				// add articles
				var startFrom = currentDisplayedArticlesIds.length + ((firstArticleId == coverArticleId) ? 1 : 0);
				var cardArticles = [];
				for (var j=0; j<noOfArticles; j++){
					var article = articles.getAt(startFrom+j);
					
					// the card is an instance of a list and it has an associated store
					card.getStore().add(article.getData());
					
					cardArticles.push(article.getData());
					
					currentDisplayedArticlesIds.push(article.get("id"));
				}
                
				card.getArticlesBox().setData(cardArticles);
				card.fireEvent("setactions");
											
				currentLayouts.push(noOfArticles);
				layouts.set("cardsLayout", currentLayouts);
				
				currentCardsIds.push(itemId);
				layouts.set("cardsIds", currentCardsIds);
				
				// if it wasn't previously generated a CSS layout then saves the one generated in the card constructor
				if (currentCssLayouts[index] == null){
					currentCssLayouts.push(card.getLayoutType());
					layouts.set("cardsCssLayout", currentCssLayouts);
				}
				
				layouts.set("displayedArticlesIds", currentDisplayedArticlesIds);
				layouts.set("nextLayoutIndex", nextLayoutIndex);
				
				//categoriesCarousel.insert(firstPos+i, card);
				this.add(card);
				
				//console.log(firstPos, i, noOfArticles, index, itemId, layouts, nextLayoutIndex, layoutsCategory.get('cardsLayout')) 
			}
		}
	},
	
	
	/* on Android Tablets (al least on 4.0 version) "landscape" and "portrait" are reversed
	   because the detection is made on window orientation property and not on window screen sizes
	   the function fixes that */
	getOrientation: function(){
		if (!Ext.os.is.Android){
			return 	Ext.Viewport.getOrientation();
		}
		
		return Ext.Viewport.getWindowWidth() > Ext.Viewport.getWindowHeight() ? "landscape" : "portrait";
	},
	
	
	/* returns the number of items we want to load by default from beginning */
	getLimit: function(){
		var sum = 0;
		for (var i=0; i<=2; i++){
			sum += this.getLayoutsPath()[i];
		}
		return sum;
	},
	
	
	onSetSizes: function(){
		if (this.getInnerWidth() == 0){
			this.setInnerWidth();
			this.setInnerHeight();	
		}
	},
	
	onRemoveAllListeners: function(){
		this.un("setsizes",this.onSetSizes);
		this.un("activeitemchange",this.onActiveItemChange);
		this.un("buildcards",this.onBuildCards);
		this.un("removealllisteners", this.onRemoveAllListeners);	
	}

});
