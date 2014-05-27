Ext.define("WP.view.phone.categories.CategoriesPanel", {
    extend: 'Ext.Panel',
	
	requires: [
		'WP.view.phone.categories.CategoriesCarousel'
	],
	
	config: {
		
		id: "categoriesPanel",
		
		// custom properties
		store: null,												// a store with info about each category displayed
		lastCarousel: null,											// the last carousel that was displayed
		currentCarousel: null,										// the current carousel that will be displayed
		coverArticleId: null,										// the id of the first article posted on cover
		
		// css properties
		cls: "categories-panel",
				
		// properties
		layout: {
			type: 'card'
		},		
		scrollable: null
    },
	
	
	initialize: function(){
		
		this.callParent(arguments);
		
		// create a store with details about displayed articles for each category
		this.setStore(Ext.create("Ext.data.ArrayStore", {
			fields: [
				{name: 'id',  					type: 'string'},							// category id
				{name: 'order',  				type: 'int'},								// category order
				{name: 'cardsLayout', 			defaultValue: []},	
				{name: 'cardsIds', 				defaultValue: []},
				{name: 'cardsCssLayout',		defaultValue: []},
				{name: 'displayedArticlesIds', 	defaultValue: []},
				{name: 'nextLayoutIndex', 		type: 'int', 		defaultValue: 0},		// the next index of the layout (from carousel's layoutsPath) to be created
				{name: 'noMoreArticles',		type: 'boolean', 	defaultValue: false} 	// a flag that indicates if all articles of a category were loaded
			],
			sorters: {
				property : 'order',
				direction: 'ASC'
			}
		}));
	}
});
