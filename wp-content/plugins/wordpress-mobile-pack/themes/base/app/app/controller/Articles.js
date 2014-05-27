var articlesController;

Ext.define('WP.controller.Articles', {
    extend: 'Ext.app.Controller',
	
	requires: [
		'WP.proxy.Article'
	],
	
	config: {
        
		refs: {
            mainView: '#mainView',
			categoriesPanel: '#categoriesPanel',
						
			articlesPanel: '#articlesPanel',
			articlesCloseBtn: {
				selector: '#articlesPanel button[action=back]',
				xtype: 'backbutton'	
			},
		},
		
		control: {
			articlesPanel: {
				addarticle: 'onAddArticle',
				buildcard: 'onBuildCard',
				showarticle: 'onShowArticle',
			},
			articlesCloseBtn:{
				tap: 'closeArticles'	
			},
		}
    },
	
	init: function(){
		articlesController = this;
    },
	
	launch: function(){
		
    },
	
	
	processArticles: function(articles){
		
		/* Each article must be parsed and adapted so that it is visible right
		   For example, field description should be adjusted and all <br> elements must be duplicated <br><br> */
		Ext.each(articles, function(article){
			
			var description = article.description;
			
			// duplicate <br>
			if (new RegExp("[^\>]\<br(|\/)\>[^\<]").test(description) == true){
				var c = description.replace(/\<br(|\/)\>\<br(|\/)\>/g, "<br/>");
					c = c.replace(/\<br(|\/)\>/g, "<br/><br/>");
				
				var splits = c.split("<br/><br/>");
				var newContent = "";
				
				for (var k=0; k<splits.length; k++){
					newContent += splits[k]
					
					if (k != splits.length-1)
						newContent += (splits[k].length > 50) ? "<br/><br/>" : "<br/>";	
				}
				
				description = newContent;
			}
			
			// replace all <a href="link" with <a href="javascript:void(0);"
			if (new RegExp("\<a.+href\=(\'|\").+(\'|\")").test(description) == true){
				var newContent = description.replace(/href\=(\'|\")\S+(\'|\")/g,"href=\"javascript:void(0);\"");
				
				description = newContent;
			}
			
			article.description = description;
			
			var date = new Date(article.date);
			var day  = date.getDate();
			var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][date.getMonth()];
			var year = date.getFullYear();
			
			var days = ["Sun","Mon", "Tue","Wed","Thu","Fri","Sat"];
			var currentYear = (new Date()).getFullYear();
			
			if (currentYear == year){
				article.date = days[date.getDay()] + ", "+ month +" "+ day;
			}
			else{
				article.date = month +" "+ day +", "+ year;
			}
		})
		
		return articles;
	},
	
	
	showArticleById: function(articleId){
		
		// get references
		var mainView = this.getMainView();
		
		var categoriesStore = mainController.categoriesStore;
		var ln = categoriesStore.getCount();
		
				
		// if there is at least one category in the store then try to load/show the article with the given id
		if (ln != 0){
			
			// get references
			var articlesPanel = this.getArticlesPanel();
			
			// try to find if the article was already created (downloaded)
			var loadedArticle = articlesPanel.getStore().findRecord("id", articleId, 0, false, true, true);		
			
			// if there is an article then create it's card
			if (loadedArticle){
				
				// activate articles panel 
				mainView.animateActiveItem(articlesPanel, {type: "slide", direction: "left"});
				
				// find the content for this article
				var categoryId = loadedArticle.get("category_id");
				var category = categoriesStore.findRecord("id", categoryId, 0, false, true, true);
				var article = category.articles().findRecord("id", articleId, 0, false, true, true);
				
				if (article){
					
					// add and show article
					articlesPanel.fireEvent("addarticle", article);
					articlesPanel.fireEvent("buildcard", article);
					articlesPanel.fireEvent("showarticle", articleId);
				}
				else{
					// remove record from articles Store
					articlesPanel.getStore().remove(loadedArticle);
					
					// reload the article
					this.loadArticle({articleId: articleId});	
				}
			}
			else{
				this.loadArticle({articleId: articleId});
			}
		}
		else{
			// if the categories store wasn't loaded yet (due to a delayed server response) then create a task and add it to queue tasks list
			if (!categoriesStore.isLoaded()){
				
				var task = Ext.create('Ext.util.DelayedTask', function() {
					WP.app.redirectTo("article/"+articleId);
				});
				
				// add task
				mainController.addQueue(task);
			}
		}
	},
	
	
	
	
	loadArticle: function(options){
		
		var articleId = options.articleId || null;
		
		// get reference
		var mainView = this.getMainView();
		var articlesPanel = this.getArticlesPanel();
		
		// add mask
		var mask = Ext.create("WP.view.LoadMask");
		Ext.Viewport.setMasked(mask);
				
		// set operation request		
		var operation = Ext.create('Ext.data.Operation', {
			model: "WP.model.Article",
			action: 'read',
			filters: {
				articleId: articleId,
				descriptionLength: (Ext.os.is.Phone) ? 300 : 500
			}
		});
		
		// create a JsonP proxy
		var proxy = Ext.create('WP.proxy.Article');
				
		// load article
		proxy.read(operation, function(response){
			if (response.success){
				
				mask.hide();
				
				var record = response.getRecords()[0];
								
				// if there is no article with the given params then exit
				if (record.get("category_id") == null){
					
					// show an error message
					
					WP.app.redirectTo("");
					return;
				}
								
				// activate panel 
				if (mainView.getActiveItem() != articlesPanel){
					mainView.animateActiveItem(articlesPanel, {type: "slide", direction: "left"});
				}
				
				// if the article wasn't loaded before then create and show it
				if (articlesPanel.getStore().findRecord("id", record.get("id"), 0, false, true, true) == null){
				
					// add and show the article
					articlesPanel.fireEvent("addarticle", record);
					articlesPanel.fireEvent("buildcard", record);
					articlesPanel.fireEvent("showarticle", record.get("id"));
				}
			}
		});
	},
	
	
	onAddArticle: function(record){
		
		// get references
		var articlesPanel = this.getArticlesPanel();
		
		var store = articlesPanel.getStore();
		store.add(record.getData());
	},
	
	
	onBuildCard: function(record){
		
		// get references
		var articlesPanel = this.getArticlesPanel();
		
		var articleId = record.get("id");
			
		// verify if there is no other card created for this article
		if (!articlesPanel.child(articleId)){
			// add a new card
			var card = Ext.create('WP.view.phone.articles.ArticleCard', {
				itemId: articleId,
				record: record,
			});
			
			articlesPanel.add(card);
			
			if (!card.getIsFilled()){
				card.onPainted();	
			}
			
			card.fireEvent("addcontent");
		}
	},
	
	
	onShowArticle: function(articleId){
		
		// get reference
		var articlesPanel = this.getArticlesPanel();
		
		// get article's card
		var articleCard = articlesPanel.child("#"+articleId);
		var index = articlesPanel.getItems().indexOf(articleCard);
		var total = articlesPanel.getItems().length;
		
		// this is the last card
		if (index == total-1){
			articlesPanel.animateActiveItem(articleCard, {type: "slide", direction: "left"});	
		}
		else{
			articlesPanel.animateActiveItem(articleCard, {type: "slide", direction: "right"});
		}
	},
	
	
	closeArticles: function(){
		var mainView = this.getMainView();
		var categoriesPanel = this.getCategoriesPanel();
		var categoriesCarousel = categoriesPanel.getCurrentCarousel();
		var articlesPanel = this.getArticlesPanel();
		var categoriesStore = mainController.categoriesStore;
		var commentsPanel = Ext.Viewport.down("#commentsPanel");
		
		// remove comments panel
		if (commentsPanel){
			commentsPanel.destroy();
		}
		
				
		// stop scrolling
		if (articlesPanel.getActiveItem().getScrollable())
			articlesPanel.getActiveItem().getScrollable().getScroller().setDisabled(true);
		
		
		var articleCard = articlesPanel.getActiveItem();
		var index = articlesPanel.getItems().indexOf(articleCard);
		var prevCard = (index > 0) ? articlesPanel.getAt(index-1) : null;
		var isLastCard = !(prevCard && prevCard.getRecord()); 
		
						
		// there is only one card in the articlesPanel
		if (isLastCard == true){
			
			var lastCategoryCard = categoriesCarousel.getActiveItem();
			
			// the lastCategoryCard is the cover
			if (lastCategoryCard.getItemId() == "cover"){
				WP.app.redirectTo("");
				mainView.animateActiveItem(categoriesPanel, {type: "slide", direction: "right"});
			}
			else{
				// the "Latest" category is active
				if (lastCategoryCard.getCategoryId() == 0){
					var categoryId = 0;
					var category = categoriesStore.findRecord("id", categoryId, 0, false, true, true);
				}
				else{
					var categoryId = (articleCard) ? articleCard.getRecord().get("category_id") : null;
					var category = categoriesStore.findRecord("id", categoryId, 0, false, true, true);
				}
				
				
				var categoryName = category.get("name_slug").replace(/\s/g,"-");
				
				// redirect to category URL
				WP.app.redirectTo("category/"+categoryName+"/"+categoryId);
			}
						
			
			// activate the last visible card only if we have the same categories
			// we do that because when the redirection is made, by default the first card of the category is automatically activated
			if (lastCategoryCard && lastCategoryCard.getCategoryId() == categoryId){
				categoriesCarousel.setActiveItem(lastCategoryCard);
			}
				
			Ext.defer(function(){
				// destroy carousel and all his children
				articleCard.destroy(true);
			}, 500, this);
		}
		// there are more than one card in the articles panel
		else{
			
			// find the id of the previous article
			var prevArticleId = prevCard.getRecord().get("id");
			
			// redirect to article URL
			Ext.defer(function(){
				WP.app.redirectTo("article/"+prevArticleId);
			}, 150, this);
			
			Ext.defer(function(){
				// destroy carousel and all its children
				articleCard.destroy(true);
			}, 500, this);
		}
	},
	
	
	openCommentsPanel: function(){
		
		// create mask
		var mask = Ext.create("WP.view.MainMask", {
			closeFn: function(){
				commentsPanel.fireEvent("closepanel");
			}
		});
		
		Ext.Viewport.add(mask);
		
		// create or show comments panel
		var profile = appticles.profile;
		var commentsPanel = Ext.Viewport.down("#commentsPanel");
		
		if (!commentsPanel){
			commentsPanel = Ext.create("WP.view."+profile+".articles.comments.CommentsPanel",{
				mask: mask,
				zIndex: mask.getZIndex()+1	
			});
			Ext.Viewport.add(commentsPanel);
			
			// set actions
			commentsPanel.fireEvent("setactions");
		}
		else{
			commentsPanel.setMask(mask);
			commentsPanel.setZIndex(mask.getZIndex()+1);
		}
		
		mask.show();
		commentsPanel.show();
	}
	
});