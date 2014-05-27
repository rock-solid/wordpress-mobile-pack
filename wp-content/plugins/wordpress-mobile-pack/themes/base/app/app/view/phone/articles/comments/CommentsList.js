Ext.define("WP.view.phone.articles.comments.CommentsList", {
  	extend: 'Ext.List',
	
	requires: [
		'WP.view.phone.articles.comments.PullRefresh'
	],
	
	config: {
		
		itemId: 'commentsList',
		
		// custom properties
		
		// css properties
		cls: 'comments-list',
		itemCls: 'comments-list-item',
		selectedCls: '',
		pressedCls: '',
		flex: 1,
		
		// properties
		scrollable: {
			direction: 'vertical',
			indicators: false
		},
		disableSelection: true,
		//emptyText: 'There are no comments for this article.<br/>Be the first one to comment about this!',
		useSimpleItems: false,
		variableHeights: true,
		itemTpl: new Ext.XTemplate(
			'<div class="comment-box">',
				'<div class="comment-left">',
					'<tpl if="avatar &amp;&amp; avatar.length &gt; 0">',
						'<div class="img" style="background-image:url(\'{avatar}\');"></div>',
					'</tpl>',
				'</div>',
				'<div class="comment-right">',
					'<div class="header">',
						'<span>{author}&nbsp;</span>',
						'<div class="info">{[this.parseDate(values.date)]}</div>',		
					'</div>',
					'<div class="body">{content}</div>',
				'</div>',
			'</div>',
			{
				parseDate: function(date){
					var date = new Date(date);
					var day  = date.getDate();
					var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][date.getMonth()];
					var year = date.getFullYear();
					
					var days = ["Sun","Mon", "Tue","Wed","Thu","Fri","Sat"];
					var currentYear = (new Date()).getFullYear();
					
					if (currentYear == year){
						return days[date.getDay()] + ", "+ month +" "+ day;
					}
					
					return month +" "+ day +", "+ year;	
				}
			}
		),
		plugins: [
			{
				xclass: 'WP.view.phone.articles.comments.PullRefresh',
			}
		],
    },
	
	
	initialize: function(){
		
		this.callParent(arguments);
		
		this.setStore(Ext.create("WP.store.Comments"));
		
		this.on("fetchlatest", this.onFetchLatest, this);
	},
	
	
	onFetchLatest: function(options){
		
		var self = this;
		var callback = options.callback || null;
		
		var store = this.getStore();
		
		// load items
		store.loadPage(1, {
			filters: { 
				articleId: this.getParent().getArticleId(),
			},
			callback: function(results, operation){
				if (callback){
					callback.call(this, operation);	
				}
			}
		});
		
		
		// destroy previous custom mask
		var mask = this.getMasked();
		if (mask){
			mask.destroy();
		}
		
		// create new custom mask
		mask = Ext.create("WP.view.LoadMask");
		this.setMasked(mask);
	}
});
