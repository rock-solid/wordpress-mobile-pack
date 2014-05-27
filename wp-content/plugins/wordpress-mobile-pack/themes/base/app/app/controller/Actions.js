var actionsController;

Ext.define('WP.controller.Actions', {
    extend: 'Ext.app.Controller',
    
    config: {
        
		refs: {
            mainView: '#mainView',
			categoriesPanel: '#mainView #categoriesPanel',
			
			actionsPanel: "#actionsPanel",
			actionsPanelBtn: {
                selector: 'button[action=view-actions-panel]',
                xtype: 'button'
            },
			categoriesList: {
				selector: "#actionsPanel #categoriesList",
				autoCreate: true
			},
            
			moreLinksList: {
				selector: "#actionsPanel #moreLinksList",
				autoCreate: true
			},
            
            creditsPanel: {
				selector: "#creditsPanel",
				autoCreate: true	
			}
		},
		
		control: {
           	actionsPanelBtn: {
                tap: 'onActionsBtnTap'
            },
			
			categoriesList:{
				itemtap: 'onCategoriesItemTap'
			},
			
			moreLinksList: {
				itemtap: 'onMoreLinksListItemTap'
			}
		}
	},
	
	
	init: function() {
		actionsController = this;
		
		this.queueTasks = [];
	},
	
	setActions: function(categoriesStore){
		
		// add categories in the store
		var categoriesList = this.getCategoriesList();
		var records = categoriesStore.getRange();
		categoriesList.getStore().add(records);
		categoriesList.setHeight(categoriesList.getItemHeight() * records.length + 10 + (records.length * 3)); 
	},
	
	
	addQueue: function(task){
		this.queueTasks.push(task);
	},
	
	runQueue: function() {
		// run tasks
		Ext.each(this.queueTasks, function(task){
			task.getFn().call();	
		})
		
		// delete tasks
		this.queueTasks = null;
	},
	
	
	onActionsBtnTap: function(){
	    
		var actionsPanel = this.getActionsPanel();
		actionsPanel.show();
		
		// create mask
		var mask = Ext.create("WP.view.MainMask", {
			spinner: false,
			closeFn: function(){
				mainView.fireEvent("closepanel");
				
				Ext.defer(function(){
					actionsPanel.hide();
				}, 400);
			}
		});
		
		var mainView = this.getMainView();
		mainView.add(mask);
		
		mainView.fireEvent("openpanel");
		
		
		// open actions panel
		Ext.defer(function(){
			// show mask
			mask.show();
		}, 100);
	},
	
		
	onCategoriesItemTap: function(list, index, item, record){
		// get references
		var mainView = this.getMainView();
		var categoriesPanel = this.getCategoriesPanel();
		var categoriesCarousel = categoriesPanel.getCurrentCarousel();
		
		// prevent double tapping
		list.suspendEvents();
					
		var nextCategory = record;
		var categoryId = record.get("id");
		
			
		// if the current displayed card belongs to the selected category than close the actions panel
		if (categoryId == categoriesCarousel.getActiveItem().getCategoryId()){
						
			// close mask and actions panel
			mainView.down("[name='mainMask']").fireEvent("close");
			
			Ext.defer(function(){
				
				// go back to the first card of the category
				var card = categoriesCarousel.getActiveItem();
				for (var i=1; i<=card.getIndex(); i++){
					categoriesCarousel.previous();
				}
			}, 500);
		}
		else{
			
			categoriesPanel.fireEvent("clearallcategorieslayouts");
					
			// build new categories carousel
			categoriesPanel.fireEvent("createcarousel", true);
						
			var categoryName = nextCategory.get("name_slug").replace(/\s/g,"-");
			var categoryId = nextCategory.get("id");
			
			// redirect to the new category URL
			WP.app.redirectTo("category/"+categoryName+"/"+categoryId);
			
			// activate new categories Carousel
			var categoriesCarousel = categoriesPanel.getCurrentCarousel();
			categoriesPanel.animateActiveItem(categoriesCarousel, {type: 'slide', direction: 'left', duration: 250});
			
			// build the cover page for the new categories carousel
			categoriesCarousel.fireEvent("addcover");
			
			Ext.defer(function(){
				// close mask and actions panel
				mainView.down("[name='mainMask']").fireEvent("close");
				
				var lastCarousel = categoriesPanel.getLastCarousel();
				
				// remove last categories carousel
				lastCarousel.fireEvent("removealllisteners");
				categoriesPanel.remove(lastCarousel, true);
			}, 500);
		}
		
		Ext.defer(function(){
			list.resumeEvents();
		}, 700)
	},
	
	
	onMoreLinksListItemTap: function(list, index, item, record){
		var cls = record.get("cls");	
		
		if (cls != ""){
			switch (cls){
				
				case "website": this.onViewWebsite(); break;
				case "credits": this.onViewCredits(); break;
				
				default: break;	
			}
		}
	},
	
	
	onViewWebsite: function(){
		if (appticles.websiteUrl){
			document.location.href = appticles.websiteUrl;
		}
    },
	
	
    onViewCredits: function(){
		
		// create mask
		var mask = Ext.create("WP.view.MainMask", {
			spinner: true,
			closeFn: function(){
				creditsPanel.fireEvent("closepanel");
			}
		});
		
		Ext.Viewport.add(mask);
		
		// create credits panel
		var profile = appticles.profile.toLowerCase();
		var creditsPanel = Ext.create("WP.view."+profile+".actions.CreditsPanel", {
			mask: mask,
			zIndex: mask.getZIndex()+1
		});
		
		Ext.Viewport.add(creditsPanel);
		
		mask.show();
		
		this.loadCredits();
    },

    loadCredits: function(){
		var me = this;
		
		Ext.data.JsonP.request({
            url: appticles.creditsPath + 'others/credits.json',
            callbackName: 'callbackCredits',
            success: function(result, request){
				
				var creditsPanel = me.getCreditsPanel();
				
				if (creditsPanel){
					// remove mask's loading spinner
					creditsPanel.getMask().fireEvent("removespinner"); 
					
					// add content
					creditsPanel.fireEvent("addcontent", result.html);
					
					// open credits panel
					creditsPanel.fireEvent("openpanel");
				}
			}
        });	
	}
});