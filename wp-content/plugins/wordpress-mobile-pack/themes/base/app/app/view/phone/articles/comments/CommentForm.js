Ext.define("WP.view.phone.articles.comments.CommentForm", {
   extend: 'Ext.form.Panel',
	
	requires: [
		'Ext.field.Email',
		'Ext.field.Text',
		'Ext.field.TextArea'
	],
	
	config: {
		
		itemId: 'commentForm',
		
		// custom properties
		inAction: false,								// prevent multiple comments
		
		// css properties
		cls: 'comment-form',
		height: 116,
		
		// properties
		docked: 'bottom',
		layout:{
			type: 'vbox',
			pack: 'justify',
			align: 'stretch'
		},
		padding: 0,
		margin: 0,
		
		// properties
		scrollable: null,
		items: [
			{
				xtype: 'textfield',
				itemId: 'nameField',
				name : 'name',
				placeHolder: 'Your Name',
				autoCapitalize: false,
				labelWidth: 0,
				clearIcon: true,
				height: 30,
			},
			{
				xtype: 'emailfield',
				itemId: 'emailField',
				name : 'email',
				placeHolder: 'Your E-mail',
				labelWidth: 0,
				clearIcon: true,
				height: 30,
			},
			{
				xtype: "panel",
				flex: 1,
				layout:{
					type: 'hbox',
					pack: 'justify',
					align: 'stretch'
				},
				items: [
					{
						xtype: 'textareafield',
						itemId: 'commentField',
						name : 'comment',
						placeHolder: 'Your comment',
						flex: 1,
						labelWidth: 0,
						scrollable: null,
						clearIcon: false
					},
					{
		
						xtype: 'panel',
						docked: 'right',
						cls: 'buttons-box',
						width: 65,
						items: [
							{
								xtype: "button",
								action: "commentarticle",
								pendingCls: "pending",												// the css button's class when the user is waiting for an action
								spinner: [															// loading spinner inside the button
									'<div id="bar-loader">',
										'<div id="block_1" class="bar_block"> </div>',
										'<div id="block_2" class="bar_block"> </div>',
										'<div id="block_3" class="bar_block"> </div>',
									'</div>'
								].join(""),
								cls: 'send-comment-button',
								pressedCls: 'pressed',
								text: "SEND",
								styleHtmlContent: true,
								handler: function(){
		
									var btn = this;
									var commentsPanel = this.up("#commentsPanel");
									var commentsList = commentsPanel.down("#commentsList");
									var form = this.up("#commentForm");
									var article = commentsPanel.getArticle();
									var articleId = commentsPanel.getArticleId();
									var textField = form.down("textareafield");
									//var message = encodeURIComponent(textField.getValue());
									
									var model = Ext.create("WP.model.Comment",{
										author: form.getValues().name,
										email: form.getValues().email,
										content: form.getValues().comment
									});
																		
									
									var errors = model.validate();
									var valid = true;
									
									if (!errors.isValid()){
										
										// the name and email address are required
										if (article.get("require_name_email") == true){
											valid = false;
											alert(errors.items[0].config.message);
										}
										else{
											for (var i=0; i<errors.items.length && valid; i++){
												var error = errors.items[i];
												var field = error.config.field;
												
												// show errors only if the email is filled with an invalid email address or the comment field is empty
												if (field != "author" && (field != "email" || (field == "email" && form.getValues().email != "" && i>=1 ))){
													valid = false;
													alert(error.config.message);
												}
											}
										}
									}
									
									if (valid){
										
										// show loading spinner
										this.addCls(this.config.pendingCls);
										this.setHtml(this.config.spinner);
										
										var successCallBack = Ext.create("Ext.util.DelayedTask", function(){
											// remove the pending cls
											btn.removeCls(btn.config.pendingCls);
											btn.setHtml("SEND");
											
											form.reset();
			
											// load the new posted comment in the comments list
											commentsList.fireEvent("fetchlatest", {
												
												callback: function(){
													// scroll to the end of the list (to the new added comment)
													var store = commentsList.getStore();
													var lastRecord = store.last();
													commentsList.scrollToRecord(lastRecord, true, false);
												}
											});
										});
			
			
										var errorCallBack = Ext.create("Ext.util.DelayedTask", function(){
											// remove the pending cls
											btn.removeCls(btn.config.pendingCls);
											btn.setHtml("SEND");
										});
										
										
										// send comment
										form.sendComment({
											articleId: articleId, 
											author: form.getValues().name,
											email: form.getValues().email,
											comment: form.getValues().comment, 
											successCallBack: successCallBack, 
											errorCallBack: errorCallBack
										});
									}
								}
							}
						]
					}	
				]
			},
		]

    },
	
	
	initialize: function(){
		
		this.callParent(arguments);
	},
	
	sendComment: function(options){
		var articleId = options.articleId || null;
		var author = options.author || null;
		var email = options.email || null;
		var comment = options.comment || null;
		var successCallBack = options.successCallBack || Ext.emptyFn;
		var errorCallBack = options.errorCallBack || Ext.emptyFn;
		
		// prevent multiple comments
		if (this.inAction) return;
		
		this.inAction = true;
		
		
		// send comment
		Ext.data.JsonP.request({
			url: appticles.exportPath + 'content.php?content=savecomment',
			params: {
				articleId: articleId,
				author: author,
				email: email,
				comment: comment,
				code: appticles.commentsToken
			},
			success: function(result, request) {
				
				if (result.status != null){
					
					switch (result.status){
						case 0:
							errorCallBack.getFn().call();
							alert("There was an error. Please try again later.");
							break;
							
						case 1:
							successCallBack.getFn().call();	
							break;
							
						case 2:
						default:
							successCallBack.getFn().call();	
							alert("Your comment is waiting moderation!");
							break;
					}
				}
				else{
					errorCallBack.getFn().call();	
				}
				
				this.inAction = false;
			},
			failure: function(){
				errorCallBack.getFn().call();
				
				this.inAction = false;
			},
			scope: this
		});	
	}
});
