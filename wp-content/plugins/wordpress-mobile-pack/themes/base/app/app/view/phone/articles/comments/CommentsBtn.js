Ext.define("WP.view.phone.articles.comments.CommentsBtn", {
    extend: 'Ext.Button',
	
	requires: [
		
	],
		   
	config: {
		
		itemId: "commentsBtn",
		
		// custom properties
				
		// css properties
		iconCls: 'comment',
		cls: 'comment-button',
		pressedCls: 'pressed',
		bottom: 0,
		right: 0,
		height: 60,
		width: 60,
		        
		// properties
		action: 'opencomments',
		html: '&nbsp;'
    },
	
	
	initialize: function(){
       	
		// add events
		this.on("tap", this.onBtnTap, this);
					   
	    this.callParent(arguments);
	},
	
	onBtnTap: function(){
		articlesController.openCommentsPanel();
	}
});
