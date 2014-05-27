Ext.define("WP.model.Article", {
   	extend: 'Ext.data.Model',
	requires: [
		'WP.proxy.Article'
	],
	
	config: {
		fields: [
			{name: 'id',  					type: 'string'},
			{name: 'title',  				type: 'string'},
			{name: 'image',  				type: 'auto'},
			{name: 'author',  				type: 'string'},
			{name: 'date',  				type: 'auto'},
			{name: 'description', 			type: 'string'},
			{name: 'content', 				type: 'string'},
			{name: 'timestamp',				type: 'int'},
			{name: 'comment_status',		type: 'string'},					// open or closed
			{name: 'require_name_email',	type: 'boolean'},					// for comments				
			{name: 'category_id',			type: 'int'},
			{name: 'category_name',			type: 'string'}
		]
	},
});
