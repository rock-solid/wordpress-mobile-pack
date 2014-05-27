Ext.define("WP.model.Comment", {
   	extend: 'Ext.data.Model',
	
	config: {
		fields: [
            {name: "id",                	type: "string"},
            {name: "author",   				type: "string"},
            {name: 'email', 				type: "string"},
			{name: "avatar", 				type: "string"},
            {name: "date",        			type: "string"},
            {name: "content",              	type: "string"},
		],
		
		validations: [
			{type:  'presence',   	name: 'author', 	message	: "Please fill your name!"},
			{type:  'presence',   	name: 'email', 		message	: "Please fill your e-mail address!"},
			{type:  'email',   		name: 'email', 		message	: "Please enter a valid e-mail address!"},
			{type : 'presence', 	name: 'content',	message : "Please type a comment!"}
		],
	},
});
