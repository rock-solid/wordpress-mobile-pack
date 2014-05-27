Ext.define("WP.model.MoreLinks", {
    extend: 'Ext.data.Model',
		
	config: {
		fields:[
			{name: 'title',  			type: 'string'},		// Link title: View desktop website, About us, etc
			{name: 'cls', 				type: 'string'},		// css class: website, about, etc
		]
	}
});
