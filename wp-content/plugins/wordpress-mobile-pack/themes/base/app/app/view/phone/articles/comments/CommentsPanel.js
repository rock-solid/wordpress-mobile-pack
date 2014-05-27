Ext.define("WP.view.phone.articles.comments.CommentsPanel", {
  	extend: 'Ext.Panel',
	
	requires: [
		'WP.store.Comments',
		'WP.view.phone.articles.comments.CommentsList',
		'WP.view.phone.articles.comments.CommentForm'
	],
	
	config: {
		
		itemId: 'commentsPanel',
		
		// custom properties
		mask: null,												// the custom mask that appears behind this panel
		article: null,
		articleId: null,
		
		// css properties
		iconCls: 'comments',
		cls: 'comments-box',
		//floatingCls: '',
		width: '100%',
		height: '100%',
		top: 0,
		layout:{
			type: 'vbox',
			pack: 'stretch',
			align: 'stretch'	
		},
		
		// properties
		fullscreen: true,
		showAnimation: {
			type: "slide",
			direction: "down",
			duration: 400,
			easing: "out"
		},
		hideAnimation: {
			type: "slideOut",
			direction: "up",
			duration: 400,
			easing: "in"
		},
		items: [
			{
				xtype: "panel",
				docked: "top",
				html: "Leave a comment",
				height: 65,
				cls: 'comments-header',
				items: [
					{
						xtype: "button",
						action: 'close',
						cls: 'close-x',
						xtype: 'button',
						pressedCls: 'pressed',
						iconCls: 'close-icon',
						height: 60,
						top: 0,
						right: 0,
						width: 60,
						align: 'right',
						html: '&nbsp;'
					}
				]
			},
		]
	},
	
	
	initialize: function(){
		
		// create comments list 
		var list = Ext.create("WP.view.phone.articles.comments.CommentsList",{
			store: Ext.create('WP.store.Comments')
		});
		this.add(list);
		
		// create comments form
		var form = Ext.create("WP.view.phone.articles.comments.CommentForm");
		this.add(form);
		
		this.callParent(arguments);
		
		this.on("setactions", this.onSetActions, this);
		this.on("loadcomments", this.onLoadComments, this);
		this.on("closepanel", this.onClosePanel, this);
		this.down("button[action=close]").on("tap", this.onCloseBtnTap, this);
	},
	
	
	onSetActions: function(){
		var articlesPanel = Ext.Viewport.down("#articlesPanel");
		var article = articlesPanel.getActiveItem().getRecord();
		
		// show or hide the comment form panel
		if (article.get("comment_status") == "open"){
			this.down("#commentForm").show();
			this.down("#commentsList").setEmptyText("There are no comments for this article.<br/>Be the first one to comment about this!")
		}
		else{
			this.down("#commentForm").hide();
			this.down("#commentsList").setEmptyText("There are no comments for this article.")
		}
		
		// load comments
		this.fireEvent("loadcomments");
	},
	
	
	onLoadComments: function(){
		var articlesPanel = Ext.Viewport.down("#articlesPanel");
		var article = articlesPanel.getActiveItem().getRecord();
		var articleId = article.getData().id;
		var commentsList = this.down("#commentsList");
		var store = commentsList.getStore();
		
		if (this.getArticleId() != articleId){
			
			// clear previous comment from the form
			var textField = this.down("textareafield");
			textField.setValue("");
			
			// remove all comments of previous article
			commentsList.getScrollable().getScroller().scrollToTop();
			store.removeAll();
			
			// remember the current articleId for which we request items
			this.setArticleId(articleId);
			this.setArticle(article);
			
			commentsList.fireEvent("fetchlatest");
		}
	},
	
	
	onOpenPanel: function(){
		this.show();
	},
	
	onClosePanel: function(){
		this.hide();
	},
		
	onCloseBtnTap: function(){
		this.getMask().fireEvent("close");	
	},
});
