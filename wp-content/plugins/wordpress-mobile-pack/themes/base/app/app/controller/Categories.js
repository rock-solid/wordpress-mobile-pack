var categoriesController;

Ext.define('WP.controller.Categories', {
    extend: 'Ext.app.Controller',
	
	requires:[
		'Ext.util.DelayedTask',
		'WP.proxy.Articles'
	],

    config: {
        
		refs: {
            mainView: '#mainView',
            actionsPanel: '#actionsPanel',
            
			categoriesPanel: '#categoriesPanel',
			articlesPanel: '#articlesPanel'
        },
		
		control: {
			categoriesPanel: {
				addcategory: 'onAddCategory',
				buildcategory: 'onBuildCategory',
				createcarousel: 'onCreateCarousel',
				clearallcategorieslayouts: 'onClearAllCategoriesLayouts'
			}			
		}
    },
	
	
	init: function() {
		categoriesController = this;
    },
	
	launch: function() {
		
    },
	
	
	processCategories: function(categories){
		Ext.each(categories, function(category){
			/* Each article must be parsed and adapted so that it is visible right
	  		   For example, field description should be adjusted and all <br> elements must be duplicated <br><br> */
			category.articles = articlesController.processArticles(category.articles);
			
			// compose the category slug name used for URL's
			category.name_slug = mainController.str2slug(category.name);
        });
		
		return categories;
	},
	
	
	setActions: function(categoriesStore){
		var categoriesPanel = this.getCategoriesPanel();
		
		Ext.each(categoriesStore.getRange(), function(category){
			var articlesStore = category.articles();
			articlesStore.setClearOnPageLoad(false);
			articlesStore.setProxy(Ext.create("WP.proxy.Articles"));
		})
		
		// create a new categories carousel
		categoriesPanel.fireEvent("createcarousel");
	},
	
	
	showCategory: function(categoryName, categoryId){
		
		// get references
		var mainView = this.getMainView();
		
		// options
		var categoryId = parseInt(categoryId);
		
		var categoriesStore = mainController.categoriesStore;
		var ln = categoriesStore.getCount();
		var error = false;
		
		mainController.isRouting = true;
		
		// if there is at least one category in the store then try to find the searched category by id
		if (ln != 0){
			var category = categoriesStore.findRecord("id", categoryId, 0, false, true, true);
			
			if (category){
				
			}
			else{
				error = true;	
			}
		}
		else{
			// if the categories store wasn't loaded yet (due to a delayed server response) then create a task and add it to the queue list
			if (!categoriesStore.isLoaded()){
				
				var task = Ext.create('Ext.util.DelayedTask', function() {
					WP.app.redirectTo("category/"+categoryName+"/"+categoryId);
				});
				
				// add task
				mainController.addQueue(task);
			}
		}
		
		// activate/build the category
		if (!error && categoriesStore.isLoaded()){
			
			// get references
			var categoriesPanel = this.getCategoriesPanel();
			categoriesPanel.fireEvent("buildcategory", categoryId);
			
			mainController.isRouting = false;
			
			// activate categories panel 
			Ext.defer(function(){
				mainView.animateActiveItem(categoriesPanel, {type: "slide", direction: "right"});
			}, 20, this);
		}
		// show an error message because the category wasn't found
		else{
			if (error){
				mainController.isRouting = false;
				
				WP.app.redirectTo("");
			
				// show an error message 
			}
		}
	},
	
	
	loadArticles: function(options){
		var self = this;
		
		if (this.isLoading) return;
		this.isLoading = true;
		
		var categoryId = (options.categoryId != null) ? options.categoryId : null;
		
		// get reference
		var categoriesPanel = this.getCategoriesPanel(); 
		var categoriesCarousel = categoriesPanel.getCurrentCarousel();
		var articlesPanel = this.getArticlesPanel();
		
		var category = mainController.categoriesStore.findRecord("id", categoryId, 0, false, true, true);
		var layouts = categoriesPanel.getStore().findRecord("id", categoryId, 0, false, true, true);
		
		// find the number of articles to request (sum of the next 3 layouts from layoutsPath)
		var layoutsPath = categoriesCarousel.getLayoutsPath();
		var nextLayoutIndex = layouts.get("nextLayoutIndex");
		
		var limit = (Ext.os.is.Phone) ? 9 : 10;
		/*for (var k=0; k<=2; k++){
			var i = nextLayoutIndex + k;
			if (i > layoutsPath.length-1){
				i = i - layoutsPath.length;	
			}
			limit += layoutsPath[i];
		}*/
		
		
		var articlesStore = category.articles();
		
		// load articles for the given category
		articlesStore.loadPage(1, {
			filters: { 
				limit: limit,
				descriptionLength: (Ext.os.is.Phone) ? 300 : 500,
				categoryId: categoryId,
				lastTimestamp: articlesStore.last().get("timestamp"),
			},
			callback: function(records, operation){
				
				// if the number of items received is less than the number of items requested, then set the flag for category
				console.log(records.length, limit)
				if (records.length < limit){
					layouts.set("noMoreArticles", true);	
				}
				
				// sync articles for all categories, except the "Latest" category
				if (categoryId == 0){
					Ext.each(records, function(record){
						var id = record.get("category_id");
						var category = mainController.categoriesStore.findRecord("id", id, 0, false, true, true);
						category.articles().add(record.getData());
					})
				}
				
				// create cards for the new loaded articles
				categoriesCarousel.fireEvent("buildcards", categoryId);
				
				self.isLoading = false;
			}
		});
	},
	
	
	onCreateCarousel: function(newCarousel){
		
		// get references
		var mainView = this.getMainView();
		var categoriesPanel = this.getCategoriesPanel(); 
		
		// get profile
		var profile = appticles.profile;
		
		// there are no carousels in the categories panel
		if (categoriesPanel.getLastCarousel() == null){
					
			// add a new carousel
			var carousel = Ext.create("WP.view."+profile+".categories.CategoriesCarousel", {
				mainView: mainView,
				categoriesPanel: categoriesPanel
			});
			
			categoriesPanel.add(carousel);
			categoriesPanel.setLastCarousel(carousel);
			categoriesPanel.setCurrentCarousel(carousel);
		}
		// this is the case when we must build a new carousel that helps us to make the sliding effect between the old and new categories cards
		else if (newCarousel == true){
			categoriesPanel.setLastCarousel(categoriesPanel.getCurrentCarousel());
			
			// add a new carousel
			var carousel = Ext.create("WP.view."+profile+".categories.CategoriesCarousel", {
				mainView: mainView,
				categoriesPanel: categoriesPanel	
			});
			categoriesPanel.add(carousel);	
			categoriesPanel.setCurrentCarousel(carousel);
		}
	},
	
	
	onBuildCategory: function(categoryId){
		
		// get references
		var mainView = this.getMainView();
		var categoriesPanel = this.getCategoriesPanel(); 
		var categoriesCarousel = categoriesPanel.getCurrentCarousel();
		
		var categoriesStore = mainController.categoriesStore;
		var category = categoriesStore.findRecord("id",categoryId, 0, false, false, true);
		
		// add category to panels's store
		categoriesPanel.fireEvent("addcategory", category);
		
		// build category's cards
		categoriesCarousel.fireEvent("buildcards", categoryId);
		
		// activate the first card of the category only if there is an URL that indicates this
		// otherwise show the cover page
		if (window.location.hash.length > 1){
			var layouts = categoriesPanel.getStore().findRecord("id", categoryId, 0, false, true, true);
			var firstCard = categoriesCarousel.child("#"+layouts.get("cardsIds")[0]);
			categoriesCarousel.setActiveItem(firstCard);
			//categoriesCarousel.animateActiveItem(firstCard, {type: "slide", direction: "left"});
		}
	},
	
	onAddCategory: function(category){
				
		// get references
		var categoriesPanel = this.getCategoriesPanel(); 
				
		// try to find if the category is already created in the panels's store
		var store = categoriesPanel.getStore();
		var layouts = store.findRecord("id", category.get("id"), 0, false, true, true);
		
		if (!layouts){
			store.add(category.getData());
			store.sort();
		}
	},
	
	
	onClearAllCategoriesLayouts: function(){
		
		// get references
		var categoriesPanel = this.getCategoriesPanel(); 
		
		var store = categoriesPanel.getStore();
		
		Ext.each(store.getRange(), function(layout){
			layout.set("cardsLayout", []);
			layout.set("cardsIds", []);
			layout.set("displayedArticlesIds", []);
			layout.set("nextLayoutIndex", 0);
		});
	}
});