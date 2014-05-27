var mainController;

Ext.define('WP.controller.Main', {
    extend: 'Ext.app.Controller',
	
	requires: [
        'WP.store.Categories',
		'WP.view.LoadMask',
		'WP.view.MainMask',
		'WP.view.BackButton'
	],
	
    config: {
		refs: {
          	mainView: '#mainView',
		},
        
        control: {
			
		},
		
		routes: {
			'': 'emptyFn',
			'category/:name/:id': 'showCategory',
			'article/:id': 'showArticleById',
		}
    },
	
	init: function(){
		mainController = this;
		
		this.categoriesStore = Ext.create("WP.store.Categories");		// categories store
		this.queueTasks = [];											// a list of tasks to be run after loading the categories store
		this.isRouting = false;											// a flag indicating if we have a "routes" process
	},
	
	
	// routes functions
	emptyFn: function(){
		
	},
	showCategory: function(categoryName, categoryId){
		categoriesController.showCategory(categoryName, categoryId);
	},
	showArticleById: function(articleId, social){
		articlesController.showArticleById(articleId, social);
	},
	
	
	launch: function() {
		
		var self = this;
		var categories = this.categoriesStore;
		
		// load categories
		categories.loadPage(1, {
			filters: { 
				limit: (Ext.os.is.Phone) ? 9 : 10,
				descriptionLength: (Ext.os.is.Phone) ? 300 : 500
			},
			callback: function(records, operation){
				self.setActions();
			}
		});
	},
    
	
	setActions: function(){
	   	
		var categoriesStore = this.categoriesStore;
		
		// set actions for each controller
		categoriesController.setActions(categoriesStore);
		actionsController.setActions(categoriesStore);
		
		// show the first category only if the routes process is false
		if (!this.isRouting){
			
			if (categoriesStore.getCount() > 0){
				var firstCategory = categoriesStore.getAt(0);
				var categoryName = firstCategory.get("name").replace(/\s/g,"-");
				var categoryId = firstCategory.get("id");
				
				// show first category
				categoriesController.showCategory(categoryName, categoryId);
			}
		}
		
		// run all the tasks added in the queue list
		this.runQueue();
	},
	
	
	addQueue: function(task){
		this.queueTasks.push(task);
	},
	
	
	runQueue: function(){
		
		// run tasks
		Ext.each(this.queueTasks, function(task){
			task.getFn().call();	
		})
		
		// delete tasks
		this.queueTasks = null;
	},
    
    	
	/* replace special characters */
	str2slug: function(str) {
			
		// remove accents, swap ñ for n, etc
		var from = "àáäâèéëêìíïîòóöôùúüûñç" + "àáäâèéëêìíïîòóöôùúüûñç".toUpperCase();
		var to   = "aaaaeeeeiiiioooouuuunc" + "aaaaeeeeiiiioooouuuunc".toUpperCase();
		for (var i=0, l=from.length ; i<l ; i++) {
			str = str.replace(
				new RegExp(from.charAt(i), 'g'),
				to.charAt(i)
			);
		}
		
		// remove invalid chars
		var from = "'!@#$%^&*:~";
		var to   = "-";
		for (var i=0, l=from.length ; i<l ; i++) {
			var c = '\\'+from.charAt(i);
			str = str.replace(new RegExp(c, 'g'), to);
		}
		
		return str;
	}
});