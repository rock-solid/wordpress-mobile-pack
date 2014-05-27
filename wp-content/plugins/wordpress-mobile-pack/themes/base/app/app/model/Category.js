Ext.define("WP.model.Category", {
    extend: 'Ext.data.Model',
	requires: [
		'WP.model.Article',
	],
	
	config: {
		fields: [
			{name: 'id',  			type: 'int'},		// category id
			{name: 'order', 		type: 'int'},		// category order
			{name: 'name',  		type: 'string'},	// category name
			{name: 'name_slug',  	type: 'string'},	// category name without special characters
			{name: 'image', 		type: 'object'},	// first article's image
			{name: 'title', 		type: 'string'},	// first articles's title
			{name: 'description', 	type: 'string'},	// first articles's description (max 60 chars)
		],
		
		hasMany: {
			model: 'WP.model.Article',
			name: 'articles',
			associationKey: 'articles',
			foreignKey: "parent_category_id"
		}
	}
});
