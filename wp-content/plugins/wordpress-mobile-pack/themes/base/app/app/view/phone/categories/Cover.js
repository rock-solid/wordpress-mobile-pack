Ext.define("WP.view.phone.categories.Cover", {
    extend: 'Ext.Panel',
	
	config: {
		
		itemId: "cover",
		
		// custom properties
		categoryId: -1,
		pressedTimeout: null,					// a delay used for changing the class of the pressed item on this card
		
		// css properties
		cls: 'cover-box',
		pressedCls: 'pressed',
				
		// properties
		scrollable: null,
		tpl: new Ext.XTemplate(
            '<div class="cover image has-cover" data-index="1" data-article-id="{[values[0].id]}" style="{[ this.getBackgroundProperties(values[0].image) ]};">',
				'<div class="bg">',
					'<div class="cover-article">',
						'<div class="title">{[values[0].title]}</div>',
						'<tpl if="values[0].date &amp;&amp; values[0].date.length &gt; 0">',
							'<div class="date">{[values[0].date]}</div>',
						'</tpl>',
					'</div>',
					'<tpl if="'+appticles.logo.length+' &gt; 0">',
						'<div class="logo-box"><img src="'+appticles.logo+'" style="width:100px;" /></div>',
					'</tpl>',
            	'</div>',
			'</div>',
            {
                getBackgroundProperties: function(image){
                    if (image && image.src.length > 0 && image.width > 0 && image.height > 0){
                       	return  'background-image: url(\'' + image.src + '\');' + 'background-size:cover;'+ 'background-position:center; background-repeat:no-repeat;';
					}
					else{
						// generate a random cover
						var rand = Math.floor(Math.random()*6+1);
						var coverImg = appticles.defaultCoversPath + "pattern-"+rand+".jpg";
						return  'background-image: url(\'' + coverImg + '\');' + 'background-size:cover;'+ 'background-position:center; background-repeat:no-repeat;';
					}
                }
            }
        )
	},
	
	
	initialize: function(){

        // add a handler for the orientationchange event of the viewport
        Ext.Viewport.on('orientationchange', 'handleOrientationChange', this, {buffer: 50 });
		
		this.on('setactions', this.onSetActions, this);
		this.on('build', this.onBuild, this);
		
		this.fireEvent("build", mainController.categoriesStore.first());
		
		this.callParent(arguments);
	},
	

	applyData: function(values){
		var arrData = [];
		for (var i=0; i<1; i++){
			var data = (typeof values[i].getData == 'function') ? values[i].getData() : values[i];
			arrData.push(data);
		}
		
		return arrData;
	},
	
	onBuild: function(firstCategory){
		this.setCategoryId(firstCategory.get("id"));
		this.setData(firstCategory.articles().getRange());
		this.fireEvent("setactions");
	},
	
    handleOrientationChange: function(){
        var data = this.getData().concat([]);
        this.setData(data);
		this.fireEvent("setactions");
    },
	
	
	onSetActions: function(){
				
		// attach tap event for each article items on this card
		var articleItems = this.element.query("div[class^=cover]");
		
		for (var k=0; k<articleItems.length; k++){
			var articleItem = Ext.get(articleItems[k]);
			articleItem.on("tap", this.onArticleItemTap, this);
			articleItem.on("touchstart", this.onArticleItemTouchStart, this);
			articleItem.on("touchend", this.onArticleItemTouchMove, this);
		}
	},
	
	
	onArticleItemTap: function(event, item){
		
		var articleItem = Ext.get(item).hasCls("cover") ? Ext.get(item) : Ext.get(item).up("div[class^=cover]");
		var articleId = articleItem.getAttribute("data-article-id");
		
		// redirect to article details page
		WP.app.redirectTo("article/"+articleId);
	},
	
	
	onArticleItemTouchStart: function(event, item){
		
		var articleItem = Ext.get(item).hasCls("cover") ? Ext.get(item) : Ext.get(item).up("div[class^=cover]");
		var articleId = articleItem.getAttribute("data-article-id");
		
		articleItem.un("touchmove", this.onArticleItemTouchMove);
		articleItem.on("touchmove", this.onArticleItemTouchMove, this);
		
		var me = this;
		this.setPressedTimeout(setTimeout(function(){
			// remove pressed item cls
			articleItem.addCls(me.getPressedCls());
		}, 100));
	},
	
	
	onArticleItemTouchMove: function(event, item){
		
		var articleItem = Ext.get(item).hasCls("cover") ? Ext.get(item) : Ext.get(item).up("div[class^=cover]");
		
		if (articleItem){
			var articleId = articleItem.getAttribute("data-article-id");
			
			delete this.getPressedTimeout();
			clearTimeout(this.getPressedTimeout());
			
			// remove pressed item cls
			articleItem.removeCls(this.getPressedCls());
			
			articleItem.un("touchmove", this.onArticleItemTouchMove);
		}
	}
});
